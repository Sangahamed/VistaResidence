<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    function index()
    {
        $data = [
            'pageTitle' => 'Acceuil'
        ];

        Log::info('Affichage de la page acceuil.', ['url' => request()->fullUrl(), 'ip' => request()->ip()]);
        return view('front.pages.index', $data);
    }

    function detail()
    {
        $data = [
            'pageTitle' => 'Info Propriete'
        ];

        Log::info('Affichage des Informations des Proprietes.', ['url' => request()->fullUrl(), 'ip' => request()->ip()]);
        return view('front.pages.detail', $data);
    }
}
