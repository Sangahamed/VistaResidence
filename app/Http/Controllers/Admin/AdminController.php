<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use App\Models\Admin;
use App\Traits\LogsActivity;
use App\Notifications\TwoFactorCodeNotification;
use Twilio\Rest\Client;

class AdminController extends Controller
{
    use LogsActivity;

    public function dashboard()
    {
        $data = [
            'pageTitle' => 'Admin Dashboard'
        ];
        $admin = Auth::guard('admin')->user();

        $this->logActivity('Admin Dashboard', ['admin_id' => $admin ? $admin->id : null]);

        return view('back.admin.home', $data);
    }

    public function loginHandler(Request $request)
    {
        $this->logActivity('LOGIN_ATTEMPT', ['login_id' => $request->login_id]);

        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string|min:5',
        ]);

        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('admin')->attempt([$fieldType => $request->login_id, 'password' => $request->password])) {
            $admin = Auth::guard('admin')->user();
            $currentDeviceInfo = $this->getCurrentDeviceInfo();

            if ($this->isNewDevice($admin, $currentDeviceInfo)) {
                $this->sendTwoFactorCode($admin, $currentDeviceInfo);
                session(['temp_admin_id' => $admin->id, 'current_device_info' => $currentDeviceInfo]);

                $this->logActivity('2FA_REQUIRED', ['admin_id' => $admin->id]);
                return redirect()->route('admin.two-factor.show');
            }

            $this->updateLoginInfo($admin, $currentDeviceInfo);
            $this->logActivity('LOGIN_SUCCESS', ['admin_id' => $admin->id]);
            return redirect()->route('admin.home');
        }

        $this->logActivity('LOGIN_FAILED', ['login_id' => $request->login_id]);
        return back()->withErrors(['login_id' => 'Identifiants incorrects']);
    }

    public function TwoFactorForm()
    {
        if (!session('temp_admin_id')) {
            return redirect()->route('admin.login')->with('fail', 'Session expirée, veuillez vous reconnecter.');
        }

        $this->logActivity('DISPLAY_2FA_FORM');
        return view('back.admin.auth.two-factor', ['pageTitle' => 'Vérification à 2 facteurs']);
    }

    public function verifyTwoFactor(Request $request)
    {
        $this->logActivity('2FA_VERIFICATION_ATTEMPT');

        $request->validate(['two_factor_code' => 'required|numeric|digits:6']);

        $admin = Admin::find(session('temp_admin_id'));

        if (!$admin || $admin->two_factor_code !== $request->two_factor_code || now()->greaterThan($admin->two_factor_expires_at)) {
            $this->logActivity('2FA_FAILED', ['admin_id' => session('temp_admin_id')]);
            return back()->withErrors(['code' => 'Code invalide ou expiré']);
        }

        $admin->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);
        Auth::guard('admin')->login($admin);
        $this->updateLoginInfo($admin, session('current_device_info'));
        session()->forget(['temp_admin_id', 'current_device_info']);

        $this->logActivity('2FA_SUCCESS', ['admin_id' => $admin->id]);
        return redirect()->route('admin.home');
    }

    public function logoutHandler(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->flash('fail', 'Vous êtes déconnecté !');

        $this->logActivity('LOGOUT', ['admin_id' => $admin ? $admin->id : null]);
        return redirect()->route('admin.login');
    }

    private function getCurrentDeviceInfo()
    {
        $agent = new Agent();
        return [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_browser' => $agent->browser(),
            'device_os' => $agent->platform(),
            'device_type' => $agent->device(),
            'is_mobile' => $agent->isMobile(),
            'is_tablet' => $agent->isTablet(),
            'is_desktop' => $agent->isDesktop(),
            'device_resolution' => request()->header('sec-ch-ua-platform'),
            'device_language' => request()->getPreferredLanguage(),
        ];
    }

    private function isNewDevice($admin, $currentDeviceInfo)
    {
        return $admin->last_login_ip !== $currentDeviceInfo['ip']
            || $admin->device_browser !== $currentDeviceInfo['device_browser']
            || $admin->device_os !== $currentDeviceInfo['device_os']
            || $admin->device_type !== $currentDeviceInfo['device_type'];
    }

    private function sendTwoFactorCode($admin, $deviceInfo)
    {
        $code = rand(100000, 999999);
        $admin->update([
            'two_factor_code' => $code, 
            'two_factor_expires_at' => now()->addMinutes(10)
        ]);

        // Détecter si l'utilisateur est sur mobile
        $isMobileDevice = $deviceInfo['is_mobile'] || $deviceInfo['is_tablet'];
        
        if ($isMobileDevice && !empty($admin->phone)) {
            // Envoyer par SMS si sur mobile et numéro disponible
            $smsSent = $this->sendTwoFactorSms($admin, $code);
            
            if ($smsSent) {
                $this->logActivity('2FA_SMS_SENT', [
                    'admin_id' => $admin->id, 
                    'phone' => $admin->phone,
                    'device_type' => $deviceInfo['device_type'],
                    'is_mobile' => true
                ]);
                
                // Optionnellement, envoyer aussi par email comme backup
                $admin->notify(new TwoFactorCodeNotification($code, 'sms_backup'));
            } else {
                // Fallback vers email si SMS échoue
                $admin->notify(new TwoFactorCodeNotification($code, 'email_fallback'));
                $this->logActivity('2FA_EMAIL_FALLBACK', [
                    'admin_id' => $admin->id,
                    'reason' => 'SMS failed'
                ]);
            }
        } else {
            // Envoyer par email si sur desktop ou pas de numéro
            $admin->notify(new TwoFactorCodeNotification($code, 'email'));
            $this->logActivity('2FA_EMAIL_SENT', [
                'admin_id' => $admin->id,
                'device_type' => $deviceInfo['device_type'],
                'is_mobile' => false
            ]);
        }

        $this->logActivity('2FA_CODE_GENERATED', [
            'admin_id' => $admin->id,
            'method' => $isMobileDevice && !empty($admin->phone) ? 'sms' : 'email',
            'device_info' => $deviceInfo
        ]);
    }

    private function sendTwoFactorSms($admin, $code)
    {
        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            
            $message = $twilio->messages->create($admin->phone, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => "🔐 VistaImmob - Votre code de vérification: {$code}\n\nCe code expire dans 10 minutes.\n\nNe partagez jamais ce code."
            ]);

            $this->logActivity('2FA_SMS_SUCCESS', [
                'admin_id' => $admin->id, 
                'phone' => $admin->phone,
                'message_sid' => $message->sid
            ]);
            
            return true;
        } catch (\Exception $e) {
            $this->logActivity('2FA_SMS_FAILED', [
                'admin_id' => $admin->id,
                'phone' => $admin->phone,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    private function updateLoginInfo($admin, $deviceInfo)
    {
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => $deviceInfo['ip'],
            'last_login_agent_user' => $deviceInfo['user_agent'],
            'device_browser' => $deviceInfo['device_browser'],
            'device_os' => $deviceInfo['device_os'],
            'device_type' => $deviceInfo['device_type'],
            'device_resolution' => $deviceInfo['device_resolution'],
            'device_language' => $deviceInfo['device_language'],
        ]);

        $this->logActivity('LOGIN_INFO_UPDATED', [
            'admin_id' => $admin->id,
            'device_info' => $deviceInfo
        ]);
    }
}
