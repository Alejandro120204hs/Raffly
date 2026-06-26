<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RifasController extends Controller
{
    public function index()
    {
        return view('admin.rifas.index', ['rifas' => $this->rifas()]);
    }

    public function show(int $id)
    {
        $rifa = collect($this->rifas())->firstWhere('id', $id);

        abort_if(!$rifa, 404);

        // Genera números vendidos de forma determinista usando el id como semilla
        $todos = range(1, $rifa['total']);
        $vendidos = array_values(array_filter($todos, function ($n) use ($rifa) {
            return (($n * 13 + $rifa['id'] * 7) % 100) < round(($rifa['vendidos'] / $rifa['total']) * 100);
        }));

        // Ajusta para que el conteo coincida exactamente
        if (count($vendidos) > $rifa['vendidos']) {
            $vendidos = array_slice($vendidos, 0, $rifa['vendidos']);
        }

        return view('admin.rifas.show', compact('rifa', 'todos', 'vendidos'));
    }

    private function rifas(): array
    {
        // premio = lo que recibe el ganador (objeto o efectivo)
        // tipo   = 'objeto' | 'efectivo'
        // precio × total > valor aproximado del premio para que sea rentable
        $raw = [
            ['id' => 1,  'nombre' => 'iPhone 15 Pro Max',        'precio' => 65000, 'vendidos' => 73,   'fecha' => '12 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'iPhone 15 Pro Max 256GB',        'loteria' => 'Lotería de Boyacá',      'cifras' => 2, 'juega' => 'Sábados',   'resultado' => null],
            ['id' => 2,  'nombre' => 'MacBook Pro M3',           'precio' => 12000, 'vendidos' => 360,  'fecha' => '20 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'MacBook Pro M3 14" 512GB',       'loteria' => 'Lotería del Tolima',     'cifras' => 3, 'juega' => 'Lunes',     'resultado' => null],
            ['id' => 3,  'nombre' => 'PlayStation 5',            'precio' => 500,   'vendidos' => 10000,'fecha' => '03 Jul 2026', 'estado' => 'finalizada', 'tipo' => 'objeto',   'premio' => 'PlayStation 5 + 3 Juegos',       'loteria' => 'Lotería de Boyacá',      'cifras' => 4, 'juega' => 'Sábados',   'resultado' => '4731'],
            ['id' => 4,  'nombre' => 'Smart TV Samsung 65"',     'precio' => 55000, 'vendidos' => 54,   'fecha' => '18 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'Smart TV Samsung 65" QLED 4K',   'loteria' => 'Lotería de Medellín',    'cifras' => 2, 'juega' => 'Viernes',   'resultado' => null],
            ['id' => 5,  'nombre' => 'Bicicleta Eléctrica',      'precio' => 6000,  'vendidos' => 230,  'fecha' => '25 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'Bicicleta Eléctrica GW Premium', 'loteria' => 'Lotería de Cundinamarca','cifras' => 3, 'juega' => 'Lunes',     'resultado' => null],
            ['id' => 6,  'nombre' => 'Efectivo $1.000.000',      'precio' => 200,   'vendidos' => 3100, 'fecha' => '15 Jul 2026', 'estado' => 'activa',     'tipo' => 'efectivo', 'premio' => '$1.000.000',                      'loteria' => 'Lotería del Tolima',     'cifras' => 4, 'juega' => 'Lunes',     'resultado' => null],
            ['id' => 7,  'nombre' => 'Viaje a Cartagena x2',     'precio' => 38000, 'vendidos' => 100,  'fecha' => '01 Jun 2026', 'estado' => 'finalizada', 'tipo' => 'objeto',   'premio' => 'Viaje todo incluido Cartagena x2','loteria' => 'Lotería de Medellín',   'cifras' => 2, 'juega' => 'Viernes',   'resultado' => '63'],
            ['id' => 8,  'nombre' => 'iPhone 14 Pro',            'precio' => 50000, 'vendidos' => 100,  'fecha' => '20 May 2026', 'estado' => 'finalizada', 'tipo' => 'objeto',   'premio' => 'iPhone 14 Pro 128GB',            'loteria' => 'Lotería de Boyacá',      'cifras' => 2, 'juega' => 'Sábados',   'resultado' => '83'],
            ['id' => 9,  'nombre' => 'Nintendo Switch OLED',     'precio' => 20000, 'vendidos' => 41,   'fecha' => '30 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'Nintendo Switch OLED + 3 Juegos','loteria' => 'Lotería de Cundinamarca','cifras' => 2, 'juega' => 'Lunes',     'resultado' => null],
            ['id' => 10, 'nombre' => 'Moto Bajaj Pulsar NS200',  'precio' => 14000, 'vendidos' => 180,  'fecha' => '05 Ago 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'Moto Bajaj Pulsar NS200 2026',   'loteria' => 'Lotería del Huila',      'cifras' => 3, 'juega' => 'Miércoles', 'resultado' => null],
            ['id' => 11, 'nombre' => 'Samsung Galaxy S24 Ultra', 'precio' => 8000,  'vendidos' => 900,  'fecha' => '28 Jul 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'Samsung Galaxy S24 Ultra 256GB', 'loteria' => 'Lotería de Bogotá',      'cifras' => 3, 'juega' => 'Jueves',    'resultado' => null],
            ['id' => 12, 'nombre' => 'Refrigerador Samsung',     'precio' => 75000, 'vendidos' => 100,  'fecha' => '10 May 2026', 'estado' => 'finalizada', 'tipo' => 'objeto',   'premio' => 'Refrigerador Samsung Family Hub', 'loteria' => 'Lotería del Tolima',     'cifras' => 2, 'juega' => 'Lunes',     'resultado' => '29'],
            ['id' => 13, 'nombre' => 'iPad Pro M4',              'precio' => 80000, 'vendidos' => 28,   'fecha' => '10 Ago 2026', 'estado' => 'activa',     'tipo' => 'objeto',   'premio' => 'iPad Pro M4 11" + Apple Pencil', 'loteria' => 'Lotería de Boyacá',      'cifras' => 2, 'juega' => 'Sábados',   'resultado' => null],
            ['id' => 14, 'nombre' => 'Efectivo $5.000.000',      'precio' => 700,   'vendidos' => 4500, 'fecha' => '15 Ago 2026', 'estado' => 'activa',     'tipo' => 'efectivo', 'premio' => '$5.000.000',                      'loteria' => 'Lotería de Medellín',    'cifras' => 4, 'juega' => 'Viernes',   'resultado' => null],
            ['id' => 15, 'nombre' => 'Cámara Sony Alpha A7 IV', 'precio' => 11000, 'vendidos' => 512,  'fecha' => '15 Abr 2026', 'estado' => 'finalizada', 'tipo' => 'objeto',   'premio' => 'Cámara Sony Alpha A7 IV + Lente', 'loteria' => 'Lotería de Cundinamarca','cifras' => 3, 'juega' => 'Lunes',     'resultado' => '512'],
        ];

        // Calcula automáticamente el total según las cifras (10^cifras)
        return array_map(function ($r) {
            $r['total'] = (int) pow(10, $r['cifras']);
            return $r;
        }, $raw);
    }
}
