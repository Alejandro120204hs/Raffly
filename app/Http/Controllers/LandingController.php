<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\User;
use Carbon\Carbon;

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
            ->orderBy('fecha', 'desc')
            ->take(4)
            ->get()
            ->map(function ($r) {
                $numeroGanador = $r->numeros()->where('numero', $r->resultado)->first();

                $ganadorNombre = 'Ganador Anónimo';
                if ($numeroGanador) {
                    if ($numeroGanador->user_id && $numeroGanador->user) {
                        $ganadorNombre = $numeroGanador->user->name;
                    } elseif ($numeroGanador->comprador_nombre) {
                        $ganadorNombre = trim($numeroGanador->comprador_nombre . ' ' . $numeroGanador->comprador_apellido);
                    }
                }

                return [
                    'nombre'  => $r->loteria,
                    'ganador' => $ganadorNombre,
                    'numero'  => $r->resultado,
                    'fecha'   => Carbon::parse($r->fecha)->format('d/m/Y'),
                    'monto'   => (int) str_replace('.', '', $r->premio),
                ];
            });

        $montoTotal = Rifa::where('estado', 'finalizada')
            ->get()
            ->sum(fn($r) => (int) str_replace('.', '', $r->premio));

        $stats = [
            'rifas_realizadas'     => Rifa::where('estado', 'finalizada')->count(),
            'usuarios_registrados' => User::where('role', 'customer')->count(),
            'premios_entregados'   => Rifa::where('estado', 'finalizada')->whereNotNull('resultado')->count(),
            'monto_total'          => $montoTotal,
        ];

        return view('welcome', compact('rifasActivas', 'ganadoresRecientes', 'stats'));
    }
}
