public function verifyTwoFactor(Request $request)
{
    $request->validate([
        'two_factor_code' => 'required|numeric|digits:6',
    ]);

    $userId = session('temp_user_id');
    $user = Admin::findOrFail($userId);

    if (!$user || $user->two_factor_code !== $request->two_factor_code || now()->greaterThan($user->two_factor_expires_at)) {
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

        if (!session()->has('temp_user_id') || !session()->has('current_device_info')) {
            Log::error('Session manquante pour la vérification 2FA.');
            return redirect()->route('login')->with('fail', 'Session expirée, veuillez vous reconnecter.');
        }

        if (now()->greaterThan($user->two_factor_expires_at)) {
            $user->two_factor_code = null; // Invalider le code après expiration
            $user->save();
            Log::warning('Code expiré.');
            return back()->with('fail', 'Code expiré, veuillez réessayer.');
        }

        if ($user->two_factor_code !== $request->two_factor_code) {
            Log::warning('Code invalide.');
            return back()->with('fail', 'Code invalide.');
        }

        $user->save();

        Auth::login($user);
        $this->updateUserLoginInfo($user, session('current_device_info'));
        $this->resetLoginAttempts($user);

        session()->forget(['temp_user_id', 'current_device_info']);

        Log::info('Vérification à deux facteurs réussie.', ['user_id' => $user->id, 'ip' => request()->ip()]);
        return redirect()->route('admin.home');
    }