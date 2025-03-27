<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ProprietaireController extends Controller
{
    public function ProprietaireDashboard()
    {

        $data = [
            'pageTitle' => 'Tableau de gestion'
        ];

        Log::info('Affichage de la page Dashboard.', ['user_id' => Auth::id(), 'ip' => request()->ip()]);
        return view('back.pages.proprietairehome', $data);
    }
}
