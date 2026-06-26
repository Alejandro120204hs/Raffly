<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'rifas_activas'      => 6,
            'rifas_finalizadas'  => 14,
            'usuarios'           => 238,
            'premios_entregados' => 18500000,
        ];

        $proximosSorteos = [
            ['nombre' => 'iPhone 15 Pro Max 256GB',    'fecha' => '12 Jul 2026', 'vendidos' => 73, 'total' => 100],
            ['nombre' => 'MacBook Pro M3 14"',          'fecha' => '20 Jul 2026', 'vendidos' => 36, 'total' => 80],
            ['nombre' => 'PlayStation 5 + 3 Juegos',   'fecha' => '03 Jul 2026', 'vendidos' => 89, 'total' => 150],
            ['nombre' => 'Smart TV Samsung 65" QLED',  'fecha' => '18 Jul 2026', 'vendidos' => 54, 'total' => 120],
            ['nombre' => 'Bicicleta Eléctrica Premium','fecha' => '25 Jul 2026', 'vendidos' => 23, 'total' => 60],
        ];

        $ultimosGanadores = [
            ['nombre' => 'Viaje a Cartagena x2',  'numero' => 47, 'premio' => 2800000],
            ['nombre' => 'iPhone 14 Pro 128GB',   'numero' => 83, 'premio' => 2100000],
            ['nombre' => 'Samsung Galaxy Tab S9', 'numero' => 12, 'premio' => 1400000],
            ['nombre' => 'AirPods Pro 2da Gen',   'numero' => 65, 'premio' => 700000],
        ];

        return view('admin.admin-dashboard', compact('stats', 'proximosSorteos', 'ultimosGanadores'));
    }
}
