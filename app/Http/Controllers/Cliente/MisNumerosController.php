<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\NumeroRifa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisNumerosController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $rifaIds = NumeroRifa::where('user_id', $userId)
            ->distinct('rifa_id')
            ->pluck('rifa_id');

        $rifas = Rifa::whereIn('id', $rifaIds)
            ->orderByRaw("FIELD(estado, 'activa', 'finalizada')")
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($r) use ($userId) {
                $numeros  = NumeroRifa::where('rifa_id', $r->id)
                                      ->where('user_id', $userId)
                                      ->orderBy('numero')
                                      ->get();
                $vendidos  = $numeros->where('estado', 'vendido')->count();
                $pendientes= $numeros->where('estado', 'pendiente')->count();

                $gane = $r->resultado
                    && $numeros->where('estado', 'vendido')
                               ->where('numero', $r->resultado)
                               ->count() > 0;

                return [
                    'id'         => $r->id,
                    'nombre'     => $r->nombre,
                    'loteria'    => $r->juega,
                    'fecha'      => Carbon::parse($r->fecha)->format('d M Y'),
                    'estado'     => $r->estado,
                    'resultado'  => $r->resultado,
                    'premio'     => $r->premio,
                    'tipo_premio'=> $r->tipo_premio ?? 'efectivo',
                    'vendidos'   => $vendidos,
                    'pendientes' => $pendientes,
                    'numeros'    => $numeros,
                    'gane'       => $gane,
                ];
            });

        return view('cliente.mis-numeros', compact('rifas'));
    }
}
