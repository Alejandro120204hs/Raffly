<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $rifasActivas = Rifa::where('estado', 'activa')
            ->orderBy('fecha_sorteo')
            ->take(6)
            ->get();

        $ganadoresRecientes = Rifa::where('estado', 'finalizada')
            ->whereNotNull('numero_ganador')
            ->latest('updated_at')
            ->take(4)
            ->get()
            ->each(function (Rifa $rifa) {
                $rifa->ganador_participacion = $rifa->participaciones()
                    ->with('user')
                    ->where('numero', $rifa->numero_ganador)
                    ->first();
            });

        $stats = [
            'rifas_realizadas'     => Rifa::where('estado', 'finalizada')->count(),
            'usuarios_registrados' => User::count(),
            'premios_entregados'   => Rifa::where('estado', 'finalizada')
                ->whereNotNull('numero_ganador')->count(),
            'monto_total'          => (int) Rifa::where('estado', 'finalizada')->sum('monto_premio'),
        ];

        return view('welcome', compact('rifasActivas', 'ganadoresRecientes', 'stats'));
    }
}
