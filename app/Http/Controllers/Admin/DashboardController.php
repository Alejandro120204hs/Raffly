<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $premiosEntregados = Rifa::where('estado', 'finalizada')
            ->get()
            ->sum(fn($r) => (int) str_replace('.', '', $r->premio));

        $stats = [
            'rifas_activas'      => Rifa::where('estado', 'activa')->count(),
            'rifas_finalizadas'  => Rifa::where('estado', 'finalizada')->count(),
            'usuarios'           => User::where('role', 'customer')->count(),
            'premios_entregados' => $premiosEntregados,
        ];

        $proximosSorteos = Rifa::where('estado', 'activa')
            ->orderBy('fecha')
            ->take(5)
            ->get()
            ->map(function ($r) {
                $total    = (int) pow(10, $r->cifras);
                $tomados  = $r->numeros()->whereIn('estado', ['vendido', 'pendiente'])->count();
                return [
                    'nombre'   => $r->nombre,
                    'loteria'  => $r->loteria,
                    'fecha'    => Carbon::parse($r->fecha)->format('d M Y'),
                    'vendidos' => $tomados,
                    'total'    => $total,
                ];
            });

        $ultimosGanadores = Rifa::where('estado', 'finalizada')
            ->whereNotNull('resultado')
            ->orderBy('fecha', 'desc')
            ->take(4)
            ->get()
            ->map(fn($r) => [
                'nombre'  => $r->loteria,
                'fecha'   => Carbon::parse($r->fecha)->format('d M Y'),
                'numero'  => $r->resultado,
                'premio'  => (int) str_replace('.', '', $r->premio),
            ]);

        return view('admin.admin-dashboard', compact('stats', 'proximosSorteos', 'ultimosGanadores'));
    }
}
