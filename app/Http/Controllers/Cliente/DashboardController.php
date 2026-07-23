<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\NumeroRifa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        /* ── KPIs ── */
        $misVendidos  = NumeroRifa::where('user_id', $userId)->where('estado', 'vendido')->count();
        $misPendientes= NumeroRifa::where('user_id', $userId)->where('estado', 'pendiente')->count();
        $misBoletas   = $misVendidos + $misPendientes;

        $rifasActivas = NumeroRifa::where('user_id', $userId)
            ->join('rifas', 'numeros_rifa.rifa_id', '=', 'rifas.id')
            ->where('rifas.estado', 'activa')
            ->distinct('numeros_rifa.rifa_id')
            ->count('numeros_rifa.rifa_id');

        $gane = NumeroRifa::where('numeros_rifa.user_id', $userId)
            ->where('numeros_rifa.estado', 'vendido')
            ->join('rifas', function ($join) {
                $join->on('numeros_rifa.rifa_id', '=', 'rifas.id')
                     ->whereColumn('rifas.resultado', 'numeros_rifa.numero');
            })
            ->count();

        /* ── Mis participaciones activas ── */
        $rifasIds = NumeroRifa::where('user_id', $userId)
            ->distinct('rifa_id')
            ->pluck('rifa_id');

        $misRifas = Rifa::whereIn('id', $rifasIds)
            ->where('estado', 'activa')
            ->orderBy('fecha')
            ->take(5)
            ->get()
            ->map(function ($r) use ($userId) {
                $misNums = NumeroRifa::where('rifa_id', $r->id)->where('user_id', $userId)->get();
                return [
                    'id'         => $r->id,
                    'nombre'     => $r->nombre,
                    'loteria'    => $r->juega,
                    'fecha'      => Carbon::parse($r->fecha)->format('d M Y'),
                    'precio'     => $r->precio,
                    'estado'     => $r->estado,
                    'vendidos'   => $r->numeros()->where('estado', 'vendido')->count(),
                    'total'      => (int) pow(10, $r->cifras),
                    'misNums'    => $misNums->pluck('numero')->toArray(),
                    'misPend'    => $misNums->where('estado', 'pendiente')->count(),
                ];
            });

        /* ── Rifas disponibles (todas las activas) ── */
        $disponibles = Rifa::where('estado', 'activa')
            ->orderBy('fecha')
            ->take(4)
            ->get()
            ->map(function ($r) use ($rifasIds) {
                return [
                    'id'        => $r->id,
                    'nombre'    => $r->nombre,
                    'loteria'   => $r->juega,
                    'fecha'     => Carbon::parse($r->fecha)->format('d M Y'),
                    'precio'    => $r->precio,
                    'vendidos'  => $r->numeros()->where('estado', 'vendido')->count(),
                    'total'     => (int) pow(10, $r->cifras),
                    'participo' => $rifasIds->contains($r->id),
                ];
            });

        return view('cliente.dashboard', compact(
            'misBoletas', 'misVendidos', 'misPendientes',
            'rifasActivas', 'gane', 'misRifas', 'disponibles'
        ));
    }
}
