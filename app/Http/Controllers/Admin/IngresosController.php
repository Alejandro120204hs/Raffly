<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\NumeroRifa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IngresosController extends Controller
{
    public function index()
    {
        /* ── KPIs globales ── */
        $totalRecaudado = DB::table('numeros_rifa')
            ->join('rifas', 'numeros_rifa.rifa_id', '=', 'rifas.id')
            ->where('numeros_rifa.estado', 'vendido')
            ->sum('rifas.precio');

        $totalPendiente = DB::table('numeros_rifa')
            ->join('rifas', 'numeros_rifa.rifa_id', '=', 'rifas.id')
            ->where('numeros_rifa.estado', 'pendiente')
            ->sum('rifas.precio');

        $totalPotencial = Rifa::where('estado', 'activa')
            ->get()
            ->sum(fn($r) => pow(10, $r->cifras) * $r->precio);

        $totalVendidas = NumeroRifa::where('estado', 'vendido')->count();

        $totalPremiosPagados = Rifa::where('estado', 'finalizada')
            ->whereNotNull('resultado')
            ->get()
            ->sum(fn($r) => (int) str_replace('.', '', $r->premio));

        $ganancia = $totalRecaudado - $totalPremiosPagados;

        /* ── Desglose por rifa ── */
        $rifas = Rifa::orderBy('created_at', 'desc')->get()->map(function ($r) {
            $vendidos   = $r->numeros()->where('estado', 'vendido')->count();
            $pendientes = $r->numeros()->where('estado', 'pendiente')->count();
            $total      = (int) pow(10, $r->cifras);
            $premioNum  = ($r->estado === 'finalizada' && $r->resultado)
                            ? (int) str_replace('.', '', $r->premio)
                            : 0;

            return [
                'id'          => $r->id,
                'nombre'      => $r->nombre,
                'loteria'     => $r->loteria,
                'estado'      => $r->estado,
                'precio'      => $r->precio,
                'total'       => $total,
                'vendidos'    => $vendidos,
                'pendientes'  => $pendientes,
                'recaudado'   => $vendidos   * $r->precio,
                'por_cobrar'  => $pendientes * $r->precio,
                'potencial'   => $total      * $r->precio,
                'premio_pago' => $premioNum,
                'ganancia'    => ($vendidos * $r->precio) - $premioNum,
                'pct'         => $total > 0 ? round(($vendidos / $total) * 100) : 0,
                'fecha'       => Carbon::parse($r->fecha)->format('d M Y'),
            ];
        });

        /* ── Ingresos por mes (últimos 6 meses) ── */
        $mesesRaw = DB::table('numeros_rifa')
            ->join('rifas', 'numeros_rifa.rifa_id', '=', 'rifas.id')
            ->where('numeros_rifa.estado', 'vendido')
            ->selectRaw("DATE_FORMAT(rifas.fecha, '%Y-%m') as mes, SUM(rifas.precio) as total")
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        $nombresMes = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $porMes = $mesesRaw->map(function ($m) use ($nombresMes) {
            [$year, $month] = explode('-', $m->mes);
            return [
                'label' => $nombresMes[(int)$month - 1] . ' ' . substr($year, 2),
                'total' => (int) $m->total,
            ];
        });

        return view('admin.ingresos.index', compact(
            'totalRecaudado', 'totalPendiente', 'totalPotencial',
            'totalVendidas', 'totalPremiosPagados', 'ganancia',
            'rifas', 'porMes'
        ));
    }
}
