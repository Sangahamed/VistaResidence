<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use constGuards;
use constDefaults;
use Illuminate\Support\Facades\File;
use App\Models\Admin;
use Toastr;

class AdminController extends Controller
{
    public function loginHandler(Request $request)
    {
        // Détecter si l'entrée est un email ou un nom d'utilisateur
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Validation des données selon le type
        $rules = [
            'login_id' => [
                'required',
                $fieldType === 'email' ? 'email' : 'string',
                "exists:admins,{$fieldType}",
            ],
            'password' => 'required|min:5|max:45',
        ];
        $messages = [
            'login_id.required' => 'L\'email ou le nom d\'utilisateur est requis',
            'login_id.email' => 'Adresse email invalide',
            'login_id.exists' => 'Les identifiants ne correspondent pas',
            'password.required' => 'Le mot de passe est requis',
        ];
        $request->validate($rules, $messages);

        // Préparer les identifiants
        $credentials = [
            $fieldType => $request->login_id,
            'password' => $request->password,
        ];

        // Tentative de connexion via le guard admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            $currentDeviceInfo = $this->getCurrentDeviceInfo();

            // Vérifier si c'est un nouvel appareil
            if ($this->isNewDevice($user, $currentDeviceInfo)) {
                Log::info('Connexion depuis un nouvel appareil détectée.', [
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'device_info' => $currentDeviceInfo,
                ]);

                $this->sendTwoFactorCode($user);
                session([
                    'temp_user_id' => $user->id,
                    'current_device_info' => $currentDeviceInfo,
                ]);

                return redirect()->route('admin.two-factor.show');
            }

            // Connexion réussie depuis un appareil déjà connu
            $this->updateUserLoginInfo($user, $currentDeviceInfo);
            $this->resetLoginAttempts($user);
            session()->regenerate(); // Sécuriser la session

            Log::info('Connexion réussie.', [
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('admin.home');
        }

        // Gestion des échecs de connexion avec limitation de tentative
        $ip = $request->ip();
        if (RateLimiter::tooManyAttempts($ip, 5)) {
            Log::warning('Trop de tentatives de connexion.', ['ip' => $ip]);
            return back()->with('fail', 'Trop de tentatives. Réessayez dans 1 minute.');
        }

        RateLimiter::hit($ip, 60); // Limite des tentatives pendant 1 minute
        Log::warning('Échec de connexion.', [
            'email' => $request->login_id,
            'ip' => $ip,
        ]);

        return back()->with('fail', 'Identifiants invalides.');
    }


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
            'mail_from_email' => env('MAIL_FROM_ADDRESS'),
            'mail_from_name' => env('MAIL_FROM_NAME'),
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

    public function TwoFactorForm()
    {
        $data = [
            'pageTitle' => 'Vérification à 2 facteurs'
        ];

    // Enregistrez un log pour confirmer l'appel
    Log::info('Affichage du formulaire de vérification 2FA.', ['url' => request()->fullUrl(), 'ip' => request()->ip()]);

    return view('back.admin.auth.two-factor', $data); // Vue spécifique pour Admin
    }

    public function verifyTwoFactor(Request $request)
{
    Log::info('Tentative de vérification du code à deux facteurs.', ['user_id' => session('temp_user_id'), 'ip' => request()->ip()]);

    $request->validate([
        'two_factor_code' => 'required|numeric|digits:6',
    ]);

    $userId = session('temp_user_id');
    $user = Admin::findOrFail($userId);

    if (!$user || $user->two_factor_code !== $request->two_factor_code || now()->greaterThan($user->two_factor_expires_at)) {
        Log::warning('Code de vérification à deux facteurs invalide ou expiré.', ['user_id' => $userId, 'ip' => request()->ip()]);
       
        return back()->with('fail', 'Code de vérification invalide ou expiré.');
    }

    // Nettoyer le code 2FA
    $user->two_factor_code = null;
    $user->two_factor_expires_at = null;
    $user->save();

    // Connecter l'utilisateur
    Auth::login($user);
    $this->updateUserLoginInfo($user, session('current_device_info'));
    $this->resetLoginAttempts($user);

    // Supprimer les données de session temporaires
    session()->forget(['temp_user_id', 'current_device_info']);

    return redirect()->route('admin.home');
}

    public function logoutHandler(Request $request){
        Auth::guard('admin')->logout();
        session()->flash('fail','Vous êtes déconnecté !');
        return redirect()->route('admin.login');
    }

    public function sendPasswordResetLink(Request $request){

        $request->validate([
            'email'=>'required|email|exists:admins,email'
        ],[
            'email.required'=>'L\'email est requis',
            'email.email'=>'Adresse email invalide',
            'email.exists'=>'L\'email n\'existe pas dans le système'
        ]);

        //Get admin details
        $admin = Admin::where('email',$request->email)->first();

        //Generate token
        $token = base64_encode(Str::random(64));

        //Check if there is an existing reset password token
        $oldToken = DB::table('password_reset_tokens')
                      ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
                      ->first();

        if( $oldToken ){
            //Update token
            DB::table('password_reset_tokens')
              ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
              ->update([
                'token'=>$token,
                'created_at'=>Carbon::now()
              ]);
        }else{
            //Add new token
            DB::table('password_reset_tokens')->insert([
                'email'=>$request->email,
                'guard'=>constGuards::ADMIN,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);
        }

        $actionLink = route('admin.reset-password',['token'=>$token,'email'=>$request->email]);

        $data = array(
            'actionLink'=>$actionLink,
            'admin'=>$admin
        );

        $mail_body = view('email-templates.admin-forgot-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email'=>env('MAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('MAIL_FROM_NAME'),
            'mail_recipient_email'=>$admin->email,
            'mail_recipient_name'=>$admin->name,
            'mail_subject'=>'Reset password',
            'mail_body'=>$mail_body
        );

        if( sendEmail($mailConfig) ){
            session()->flash('success','Nous avons envoyé un lien de réinitialisation de mot de passe par email.');
            return redirect()->route('admin.forgot-password');
        }else{
            session()->flash('fail','Une erreur est survenue !');
            return redirect()->route('admin.forgot-password');
        }
    }

    public function resetPassword(Request $request, $token = null){
        $check_token = DB::table('password_reset_tokens')
                         ->where(['token'=>$token,'guard'=>constGuards::ADMIN])
                         ->first();

        if( $check_token ){
            //Check if token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if( $diffMins > constDefaults::tokenExpiredMinutes ){
               //If token expired
               session()->flash('fail','Lien expiré, demandez un autre lien de réinitialisation de mot de passe.');
               return redirect()->route('admin.forgot-password',['token'=>$token]);
            }else{
                return view('back.pages.admin.auth.reset-password')->with(['token'=>$token]);
            }
        }else{
            session()->flash('fail','Lien invalide ! Demandez un autre lien de réinitialisation de mot de passe.');
            return redirect()->route('admin.forgot-password',['token'=>$token]);
        }
    }

    public function resetPasswordHandler(Request $request){
        $request->validate([
            'new_password'=>'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation'=>'required'
        ]);

        $token = DB::table('password_reset_tokens')
                   ->where(['token'=>$request->token,'guard'=>constGuards::ADMIN])
                   ->first();

        //Get admin details
        $admin = Admin::where('email',$token->email)->first();

        //Update admin password
        Admin::where('email',$admin->email)->update([
            'password'=>Hash::make($request->new_password)
        ]);

        //Delete token record
        DB::table('password_reset_tokens')->where([
            'email'=>$admin->email,
            'token'=>$request->token,
            'guard'=>constGuards::ADMIN
        ])->delete();

        //Send email to notify admin
        $data = array(
            'admin'=>$admin,
            'new_password'=>$request->new_password
        );

        $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email'=>env('MAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('MAIL_FROM_NAME'),
            'mail_recipient_email'=>$admin->email,
            'mail_recipient_name'=>$admin->name,
            'mail_subject'=>'Password changed',
            'mail_body'=>$mail_body
        );

        sendEmail($mailConfig);
        return redirect()->route('admin.login')->with('success','Fait ! Votre mot de passe a été modifié. Utilisez le nouveau mot de passe pour vous connecter au système.');
    }

    public function profileView(Request $request){
        $admin = null;
        if( Auth::guard('admin')->check() ){
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.profile', compact('admin'));
    }

    public function changeProfilePicture(Request $request){
        $admin = Admin::findOrFail(auth('admin')->id());
        $path = 'images/users/admins/';
        $file = $request->file('adminProfilePictureFile');
        $old_picture = $admin->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $filename = 'ADMIN_IMG_'.rand(2,1000).$admin->id.time().uniqid().'.jpg';

        $upload = $file->move(public_path($path),$filename);

        if($upload){
            if( $old_picture != null && File::exists(public_path($path.$old_picture)) ){
                File::delete(public_path($path.$old_picture));
            }
            $admin->update(['picture'=>$filename]);
            return response()->json(['status'=>1,'msg'=>'Votre photo de profil a été mise à jour avec succès.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Une erreur est survenue , Veuillez réessayer']);
        }
    }

    public function changeLogo(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_logo');
        $settings = new GeneralSetting();
        $old_logo = $settings->first()->site_logo;
        $file_path = $path.$old_logo;
        $filename = 'LOGO_'.uniqid().'.'.$file->getClientOriginalExtension();

        $upload = $file->move(public_path($path),$filename);

        if( $upload ){
            if( $old_logo != null && File::exists(public_path($path.$old_logo)) ){
                File::delete(public_path($path.$old_logo));
            }
            $settings = $settings->first();
            $settings->site_logo = $filename;
            $update = $settings->save();

            return response()->json(['status'=>1,'msg'=>'Logo du Site a été mise à jour avec succès.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Une erreur est survenue , Veuillez réessayer.']);
        }
    }

    public function changeFavicon(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_favicon');
        $settings = new GeneralSetting();
        $old_favicon = $settings->first()->site_favicon;
        $filename = 'FAV_'.uniqid().'.'.$file->getClientOriginalExtension();

        $upload = $file->move(public_path($path), $filename);

        if( $upload ){
           if( $old_favicon != null && File::exists(public_path($path.$old_favicon)) ){
             File::delete(public_path($path.$old_favicon));
           }
           $settings = $settings->first();
           $settings->site_favicon = $filename;
           $update = $settings->save();

           return response()->json(['status'=>1,'msg'=>'Favicon du site a été mise à jour avec succès.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Une erreur est survenue , Veuillez réessayer.']);
        }
    }

     public function showUsers()
    {
        $data = [
             'pageTitle' => 'liste des utlisateur',
            ];
        if( Auth::guard('admin')->check() ){
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.userlist',$data);
    }

    public function showLocation()
    {
        $data = [
             'pageTitle' => 'liste des vehicule en location',

            ];
        if( Auth::guard('admin')->check() ){
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.locationlist',$data);
    }

    public function showVendre()
    {

        $data = [
             'pageTitle' => 'liste des vehicule en vente',

            ];
        if( Auth::guard('admin')->check() ){
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.vendrelist',$data);
    }

    public function deleteVendre(Request $request)
        {
                $vendre = Vendre::findOrFail($request->id);

                // Supprimer l'image visite
                $pathVisite = 'images/Vendre/image_viste/';
                if ($vendre->image_viste && File::exists(public_path($pathVisite . $vendre->image_viste))) {
                    File::delete(public_path($pathVisite . $vendre->image_viste));
                }

                // Supprimer les images de véhicule à vendre
                $pathVente = 'images/Vendre/';
                if ($vendre->imagevehiculevente) {
                    $imageFilenames = json_decode($vendre->imagevehiculevente);
                    foreach ($imageFilenames as $filename) {
                        if (File::exists(public_path($pathVente . $filename))) {
                            File::delete(public_path($pathVente . $filename));
                        }
                    }
                }

                // Supprimer l'enregistrement Vendre
                $delete = $vendre->delete();

                if ($delete) {
                    return response()->json(['status' => 1, 'msg' => 'La vente et ses images associées ont été supprimées avec succès.']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Une erreur est survenue lors de la suppression de la vente.']);
                }
     }


     public function deleteLocation(Request $request)
            {
                $location = Location::findOrFail($request->id);

                // Supprimer l'image visite
                $pathVisite = 'images/Locations/carte_assurance/';
                if ($location->image_assurance && File::exists(public_path($pathVisite . $location->image_assurance))) {
                    File::delete(public_path($pathVisite . $location->image_assurance));
                }

                // Supprimer les images de véhicule à location
                $pathLocation = 'images/locations/';
                if ($location->location_images) {
                    $imageFilenames = json_decode($location->location_images);
                    foreach ($imageFilenames as $filename) {
                        if (File::exists(public_path($pathLocation . $filename))) {
                            File::delete(public_path($pathLocation . $filename));
                        }
                    }
                }

                // Supprimer l'enregistrement location
                $delete = $location->delete();

                if ($delete) {
                    return response()->json(['status' => 1, 'msg' => 'La location et ses images associées ont été supprimées avec succès.']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Une erreur est survenue lors de la suppression de la vente.']);
                }
     }

     public function deleteUser(Request $request)
            {
                $user = User::findOrFail($request->id);

                // Supprimer l'image profile
                $pathVisite = 'images/users/';
                if ($user->picture && File::exists(public_path($pathVisite . $user->picture))) {
                    File::delete(public_path($pathVisite . $user->picture));
                }

                // Supprimer l'enregistrement location
                $delete = $user->delete();

                if ($delete) {
                    return response()->json(['status' => 1, 'msg' => 'Le client a ete supprimer et ses images associées ont été supprimées avec succès.']);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Une erreur est survenue lors de la suppression de la vente.']);
                }
     }
}
