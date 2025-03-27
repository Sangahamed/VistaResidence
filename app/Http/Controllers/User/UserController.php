<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Laravel\Socialite\Facades\Socialite;
use constDefaults;
use Toastr;


class UserController extends Controller

{

    // 1
    function RegisterFrom()
    {
        $data = [
            'pageTitle' => 'Inscription'
        ];

        Log::info('Affichage du formulaire d\'inscription.', ['url' => request()->fullUrl(), 'ip' => request()->ip()]);
        return view('front.auth.register', $data);
    }

    //   2
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Log::info('Nouvelle tentative d\'inscription.', ['email' => $request->email, 'ip' => request()->ip()]);

        $user = new User();
        $user->fill($request->only(['name', 'phone', 'email']));
        $user->password = Hash::make($request->password);
        $user->status = 'Pending';
        $this->setUserDeviceInfo($user);

        $user->assignRole('client');

        if ($user->save()) {
            $token = $this->createVerificationToken($user);
            $this->sendVerificationEmail($user, $token);
            session(['unverified_email' => $user->email]);

            Log::info('Inscription réussie, email de vérification envoyé.', ['email' => $user->email, 'user_id' => $user->id, 'ip' => request()->ip()]);
            return redirect()->route('verification.notice')->with('success', 'Vous avez reçu un lien de vérification. Consultez votre email.');
        }

        Log::error('Échec de l\'inscription.', ['email' => $request->email, 'ip' => request()->ip(), 'errors' => $user->errors()->toArray()]);
        return back()->with('fail', 'Une erreur s\'est produite lors de l\'enregistrement.');
    }

    // 3
    public function verifyNotice(Request $request)
    {
        $data = [
            'pageTitle' => 'verification de l\'email'
        ];

        Log::info('Affichage de la page de vérification de l\'email.', ['ip' => request()->ip()]);

        return view('front.auth.verify', $data);
    }

    // 4
    public function LoginFrom()
    {
        $data = [
            'pageTitle' => 'Connexion'
        ];

        Log::info('Affichage du formulaire de connexion.', ['url' => request()->fullUrl()]);
        return view('front.auth.login', $data);
    }

    // 5
    public function TwoFactorForm()
    {
        $data = [
            'pageTitle' => 'Verification a 2Facteur'
        ];
        Log::info('Affichage de la page de vérification a 2 Facteur.', ['url' => request()->fullUrl(), 'ip' => request()->ip()]);

        return view('front.auth.two-factor');
    }

    // 6
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email|max:255',
            'password' => 'bail|required|string|min:8|max:255',
        ]);


        // Rate limiting
        $ip = $request->ip();
        Log::info('Tentative de connexion', ['email' => $request->email, 'ip' => $request->ip()]);

        if (RateLimiter::tooManyAttempts($ip, 5)) {
            Log::warning('Trop de tentatives de connexion', ['ip' => $ip, 'email' => $request->email]);
            return back()->with('fail', 'Trop de tentatives. Réessayez dans 1 minute.');
        }


        $user = User::where('email', $request->email)->first();



        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($ip, 60);
            Log::warning('Échec de connexion', ['email' => $request->email, 'ip' => $ip, 'reason' => $user ? 'Mot de passe incorrect' : 'Utilisateur non trouvé']);
            if ($user) {
                $this->handleFailedLogin($user);
            }
            return back()->with('fail', 'Identifiants invalides.');
        }

        // Vérification de l'email
        if (!$user->verified) {
            Log::info('Connexion échouée : compte non vérifié.', ['email' => $user->email, 'ip' => $ip]);
            return back()->with('fail', 'Votre email n\'est pas vérifié. Veuillez vérifier votre email.');
        }

        // Suspicious login check
        if ($this->isSuspiciousLoginAttempt($user)) {
            Log::warning('Tentative de connexion suspecte', ['email' => $user->email, 'ip' => $ip, 'failed_attempts' => $user->failed_login_attempts]);
            return back()->with('fail', 'Nombre excessif de tentatives de connexion. Veuillez patienter.');
        }

        // Device & 2FA handling
        $currentDeviceInfo = $this->getCurrentDeviceInfo();
        if ($this->isNewDevice($user, $currentDeviceInfo)) {
            Log::info('Connexion à partir d\'un nouvel appareil détectée.', ['email' => $user->email, 'ip' => $ip, 'device_info' => $currentDeviceInfo]);
            $this->sendTwoFactorCode($user);
            session(['temp_user_id' => $user->id, 'current_device_info' => $currentDeviceInfo]);
            return redirect()->route('two-factor.show');
        }

        // Authentication success
        Auth::login($user, $request->remember);
        $this->updateUserLoginInfo($user, $currentDeviceInfo);
        $this->resetLoginAttempts($user);
        session()->regenerate(); // Secure session

        if ($user->hasPermissionTo('can_post_ads')) {
            return redirect()->route('proprietaire.dashboard');
        } elseif ($user->hasRole('admin_entreprise')) {
            return redirect()->route('entreprise.dashboard');
        }

        Log::info('Connexion réussie.', ['email' => $user->email, 'ip' => $ip, 'user_id' => $user->id]);
        return redirect()->route('dashboard');
    }


    // 7
    public function HomeDashboard()
    {

        $data = [
            'pageTitle' => 'Tableau de gestion'
        ];

        Log::info('Affichage de la page Dashboard.', ['user_id' => Auth::id(), 'ip' => request()->ip()]);
        return view('back.pages.home', $data);
    }

   
    public function logout(Request $request)
    {
        Auth::user->logout();
        Log::info('Deconnection de .', ['name' => $request->name, 'user_id' => Auth::id(), 'ip' => request()->ip()]);
        return redirect()->route('login')->with('fail', 'Vous etes deconnecter !');
    }


    // 8
    public function redirectToProvider($provider)
    {
        Log::info('Redirection vers le fournisseur d\'authentification', ['provider' => $provider, 'ip' => request()->ip()]);
        return Socialite::driver($provider)->redirect();
    }

    // 9
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            Log::info('Récupération réussie des informations utilisateur depuis le fournisseur', ['provider' => $provider, 'email' => $socialUser->getEmail()]);
        } catch (\Exception $e) {
            Log::error('Échec de la récupération des informations utilisateur depuis le fournisseur', ['provider' => $provider, 'error' => $e->getMessage()]);

            return redirect('/login')->withErrors(['error' => 'Une erreur est survenue lors de la connexion avec ' . $provider]);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            Auth::login($user);
        } else {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
                $this->updateUserLoginInfo($user, $currentDeviceInfo),
            ]);
        }

        Auth::login($user, true);
        Log::info('Connexion réussie depuis le fournisseur d\'authentification', ['provider' => $provider, 'email' => $user->email, 'user_id' => $user->id, 'ip' => request()->ip()]);
        return redirect()->intended(route('dashboard'));
    }

    public function forgotPassword(Request $request)
    {
        $data = [
            'pageTitle' => 'Mot de passe oublie'
        ];
        return view('front.auth.forgot', $data);
    } //End Method

    public function sendPasswordResetLink(Request $request)
    {
        //Validate the form
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'L\'email est requis',
            'email.email' => 'Adresse email invalide',
            'email.exists' => 'L\'email n\'est pas reconnue'
        ]);

        //Get User details
        $user = User::where('email', $request->email)->first();

        //Generate token
        $token = base64_encode(Str::random(64));

        //Check if there is an existing reset password token for this user
        $oldToken = DB::table('password_reset_tokens')
            ->where(['email' => $user->email])
            ->first();

        if ($oldToken) {
            //UPDATE EXISTING TOKEN
            DB::table('password_reset_tokens')
                ->where(['email' => $user->email])
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            //INSERT NEW RESET PASSWORD TOKEN
            DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        }

        $actionLink = route('user.reset-password', ['token' => $token, 'email' => urlencode($user->email)]);

        $data['actionLink'] = $actionLink;
        $data['user'] = $user;
        $mail_body = view('email-templates.user-forgot-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Reset Password',
            'mail_body' => $mail_body
        );

        if (sendEmail($mailConfig)) {
            return redirect()->route('user.forgot-password')->with('success', 'Le lien de réinitialisation de mot de passe a été envoyé.');
        } else {
            return redirect()->route('user.forgot-password')->with('fail', 'Une erreur est survenue , Veuillez réessayer.');
        }
    } //End Method

    public function showResetForm(Request $request, $token = null)
    {
        //Check if token exists
        $get_token = DB::table('password_reset_tokens')
            ->where(['token' => $token])
            ->first();

        if ($get_token) {
            //Check if this token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $get_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > constDefaults::tokenExpiredMinutes) {
                //When token is older that 15 minutes
                return redirect()->route('user.forgot-password', ['token' => $token])->with('fail', 'Lien expiré ! Demandez un autre lien de réinitialisation du mot de passe.');
            } else {
                return view('back.pages.user.auth.reset')->with(['token' => $token]);
            }
        } else {
            return redirect()->route('user.forgot-password', ['token' => $token])->with('fail', 'Lien invalide !, demandez un autre lien de réinitialisation du mot de passe.');
        }
    } //End Method

    public function resetPasswordHandler(Request $request)
    {
        //Validate the form
        $request->validate([
            'new_password' => 'required|min:8|max:45|required_with:confirm_new_password|same:confirm_new_password',
            'confirm_new_password' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where(['token' => $request->token])
            ->first();

        //Get user details
        $user = User::where('email', $token->email)->first();

        //Update user password
        User::where('email', $user->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        //Delete token record
        DB::table('password_reset_tokens')->where([
            'email' => $user->email,
            'token' => $request->token
        ])->delete();

        //Send email to notify user for new password
        $data['user'] = $user;
        $data['new_password'] = $request->new_password;

        $mail_body = view('email-templates.user-reset-email-template', $data);

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Password Changed',
            'mail_body' => $mail_body
        );

        sendEmail($mailConfig);
        return redirect()->route('user.login')->with('success', 'Votre mot de passe a été modifié. Utilisez le nouveau mot de passe pour vous connecter.');
    }

    // 10
    private function createVerificationToken($user)
    {
        $token = hash('sha256', Str::random(64));
        VerificationToken::create([
            'user_type' => 'user',
            'email' => $user->email,
            'token' => $token
        ]);
        Log::info('Token de vérification créé', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        return $token;
    }

    // 11
    private function sendVerificationEmail($user, $token)
    {
        $actionLink = route('verify.account', ['token' => $token]);
        $data = [
            'action_link' => $actionLink,
            'user_name' => $user->name,
            'user_email' => $user->email
        ];

        $mail_body = view('email-templates.user-verify-template', $data)->render();
        sendEmail([
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Verify User Account',
            'mail_body' => $mail_body
        ]);
        Log::info('Email de vérification envoyé', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
    }

    // 12
    public function verifyAccount($token)
    {
        Log::info('Tentative de vérification de compte avec token.', ['token' => $token, 'ip' => request()->ip()]);

        $verifyToken = VerificationToken::where('token', $token)->first();

        if (!$verifyToken || $verifyToken->created_at->diffInHours(now()) > 24) {
            VerificationToken::where('token', $token)->delete();
            Log::warning('Token de vérification invalide ou expiré.', ['token' => $token]);
            return redirect()->route('register')->with('fail', 'Le lien de vérification est invalide ou a expiré.');
        }

        $user = User::where('email', $verifyToken->email)->first();

        if (!$user) {
            Log::warning('Utilisateur non trouvé pour la vérification.', ['email' => $verifyToken->email]);

            return redirect()->route('register')->with('fail', 'Utilisateur non trouvé.');
        }

        if (!$user->verified) {
            $user->verified = true;
            $user->email_verified_at = now();
            $user->save();
        }

        $verifyToken->delete();
        Log::info('Compte vérifié avec succès.', ['email' => $user->email]);

        return redirect()->route('login')->with('success', 'Votre email est vérifié. Connectez-vous');
    }

    // 13
    public function resendVerification(Request $request)
    {
        Log::info('Demande de renvoi du lien de vérification.', ['ip' => request()->ip()]);
        // Récupérer l'email depuis la session
        $email = session('unverified_email');

        if (!$email) {
            Log::info('Aucun utilisateur non vérifié trouvé pour le renvoi de vérification.', ['ip' => request()->ip()]);
            return redirect()->route('register')
                ->with('fail', 'Aucun utilisateur non vérifié trouvé. Veuillez vous inscrire.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('Utilisateur non trouvé pour le renvoi de vérification.', ['email' => $email, 'ip' => request()->ip()]);
            return redirect()->route('register')
                ->with('fail', 'Utilisateur non trouvé. Veuillez vous inscrire.');
        }

        if ($user->verified) {
            Log::info('Tentative de renvoi de vérification pour un compte déjà vérifié.', ['email' => $user->email, 'ip' => request()->ip()]);
            return redirect()->route('dashboard')
                ->with('info', 'Votre compte est déjà vérifié.');
        }

        // Supprimer les anciens tokens
        VerificationToken::where('email', $user->email)->delete();

        // Créer un nouveau token de vérification
        $token = $this->createVerificationToken($user);

        // Envoyer l'e-mail de vérification
        $this->sendVerificationEmail($user, $token);

        Log::info('Nouveau lien de vérification envoyé.', ['email' => $user->email, 'ip' => request()->ip()]);
        return back()->with('success', 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail.');
    }

    // 14
    private function setUserDeviceInfo($user)
    {
        $agent = new Agent();
        $user->last_login_at = now();
        $user->last_login_ip = request()->ip();
        $user->last_login_agent_user = request()->userAgent();
        $user->device_type = $agent->device();
        $user->device_os = $agent->platform();
        $user->device_browser = $agent->browser();
        $user->device_resolution = request()->header('sec-ch-ua-platform');
        $user->device_language = request()->getPreferredLanguage();
        Log::info('Définition des informations de l\'appareil de l\'utilisateur', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $user->last_login_ip,
            'device_type' => $user->device_type,
            'device_os' => $user->device_os,
            'device_browser' => $user->device_browser
        ]);
    }

    // 15
    private function getCurrentDeviceInfo()
    {
        $agent = new Agent();
        $deviceInfo = [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => $agent->device(),
            'device_os' => $agent->platform(),
            'device_browser' => $agent->browser(),
            'device_resolution' => request()->header('sec-ch-ua-platform'),
            'device_language' => request()->getPreferredLanguage(),
        ];
        Log::info('Informations sur l\'appareil actuel récupérées', $deviceInfo);
        return $deviceInfo;
    }

    // 16
    private function isNewDevice($user, $currentDeviceInfo)
    {
        $isNew = $user->last_login_ip !== $currentDeviceInfo['ip'] ||
            $user->last_login_agent_user !== $currentDeviceInfo['user_agent'] ||
            $user->device_type !== $currentDeviceInfo['device_type'] ||
            $user->device_os !== $currentDeviceInfo['device_os'] ||
            $user->device_browser !== $currentDeviceInfo['device_browser'] ||
            $user->device_resolution !== $currentDeviceInfo['device_resolution'] ||
            $user->device_language !== $currentDeviceInfo['device_language'];

        if ($isNew) {
            Log::info('Nouvel appareil détecté pour l\'utilisateur', [
                'user_id' => $user->id,
                'email' => $user->email,
                'new_device_info' => $currentDeviceInfo
            ]);
        }

        return $isNew;
    }

    // 17
    private function updateUserLoginInfo($user, $deviceInfo)
    {
        $user->last_login_at = now();
        $user->last_login_ip = $deviceInfo['ip'];
        $user->last_login_agent_user = $deviceInfo['user_agent'];
        $user->device_type = $deviceInfo['device_type'];
        $user->device_os = $deviceInfo['device_os'];
        $user->device_browser = $deviceInfo['device_browser'];
        $user->device_resolution = $deviceInfo['device_resolution'];
        $user->device_language = $deviceInfo['device_language'];
        $user->save();
        Log::info('Mise à jour des informations de connexion de l\'utilisateur', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $deviceInfo['ip'],
            'device_type' => $deviceInfo['device_type'],
            'device_os' => $deviceInfo['device_os'],
            'device_browser' => $deviceInfo['device_browser']
        ]);
    }

    // 18
    private function sendTwoFactorCode($user)
    {
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        $data = [
            'user_name' => $user->name,
            'two_factor_code' => $user->two_factor_code,
        ];

        $mail_body = view('email-templates.two-factor-code', $data)->render();
        sendEmail([
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Votre code de vérification',
            'mail_body' => $mail_body
        ]);
        Log::info('Code de vérification à deux facteurs envoyé', [
            'user_id' => $user->id,
            'email' => $user->email,
            'expires_at' => $user->two_factor_expires_at
        ]);
    }

    // 19
    public function verifyTwoFactor(Request $request)
    {
        Log::info('Tentative de vérification du code à deux facteurs.', ['user_id' => session('temp_user_id'), 'ip' => request()->ip()]);

        $request->validate([
            'two_factor_code' => 'required|numeric|digits:6',
        ]);

        $userId = session('temp_user_id');
        $user = User::findOrFail($userId);

        if (!$user || $user->two_factor_code !== $request->two_factor_code || now()->greaterThan($user->two_factor_expires_at)) {
            Log::warning('Code de vérification à deux facteurs invalide ou expiré.', ['user_id' => $userId, 'ip' => request()->ip()]);

            return back()->with('fail', 'Code de vérification invalide ou expiré.');
        }

        $user->save();

        Auth::login($user);
        $this->updateUserLoginInfo($user, session('current_device_info'));
        $this->resetLoginAttempts($user);

        session()->forget(['temp_user_id', 'current_device_info']);

        Log::info('Vérification à deux facteurs réussie.', ['user_id' => $user->id, 'ip' => request()->ip()]);
        return redirect()->route('dashboard');
    }

    // 20
    private function handleFailedLogin($user)
    {
        $user->increment('failed_login_attempts');
        $user->last_failed_login_at = now();
        $user->save();
        Log::warning('Échec de connexion enregistré ', [
            'user_id' => $user->id,
            'email' => $user->email,
            'failed_attempts' => $user->failed_login_attempts,
            'ip' => request()->ip()
        ]);
    }

    // 21
    private function isSuspiciousLoginAttempt($user)
    {
        $isSuspicious = $user->failed_login_attempts >= 10 && $user->last_failed_login_at->diffInMinutes(now()) < 15;
        if ($isSuspicious) {
            Log::warning('Tentative de connexion suspecte détectée', [
                'user_id' => $user->id,
                'email' => $user->email,
                'failed_attempts' => $user->failed_login_attempts,
                'last_failed_attempt' => $user->last_failed_login_at,
                'ip' => request()->ip()
            ]);
        }
        return $isSuspicious;
    }

    // 22
    private function resetLoginAttempts($user)
    {
        $user->failed_login_attempts = 0;
        $user->save();
        Log::info('Réinitialisation des tentatives de connexion', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);
    }

//    public function activateProprietaire(Request $request)
// {
//     $user = $request->user();
    
//     if (!$user->hasRole('particulier')) {
//         $user->assignRole('particulier');
//         $user->removeRole('client');
//         return redirect()->route('proprietaire.dashboard');
//     }
    
//     return back();
// }

    public function createEnterprise(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $entreprise = Enterprise::create([
            'name' => $request->name,
            'admin_id' => $request->user()->id,
        ]);

        $request->user()->assignRole('admin_entreprise');
        return redirect()->route('entreprise.dashboard');
    }
}
