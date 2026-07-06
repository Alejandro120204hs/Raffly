<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $rifasActivas = Rifa::where('estado', 'activa')
            ->orderBy('fecha')
            ->take(6)
            ->get();

        $ganadoresRecientes = Rifa::where('estado', 'finalizada')
            ->whereNotNull('resultado')
            ->latest('updated_at')
            ->take(4)
            ->get();

        $stats = [
            'rifas_realizadas'     => Rifa::where('estado', 'finalizada')->count(),
            'usuarios_registrados' => User::count(),
            'premios_entregados'   => Rifa::where('estado', 'finalizada')->whereNotNull('resultado')->count(),
            'monto_total'          => 0,
        ];

        return view('welcome', compact('rifasActivas', 'ganadoresRecientes', 'stats'));
    }
}
