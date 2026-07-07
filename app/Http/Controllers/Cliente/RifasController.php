<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\NumeroRifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RifasController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $rifas = Rifa::where('estado', 'activa')
            ->orderBy('fecha')
            ->get()
            ->map(function ($r) use ($userId) {
                $total       = (int) pow(10, $r->cifras);
                $vendidos    = $r->numeros()->where('estado', 'vendido')->count();
                $disponibles = $r->numeros()->where('estado', 'disponible')->count();
                $misNums     = NumeroRifa::where('rifa_id', $r->id)
                                         ->where('user_id', $userId)
                                         ->count();

                return [
                    'id'         => $r->id,
                    'nombre'     => $r->nombre,
                    'loteria'    => $r->juega,
                    'fecha'      => Carbon::parse($r->fecha)->format('d M Y'),
                    'precio'     => $r->precio,
                    'premio'     => $r->premio,
                    'total'      => $total,
                    'vendidos'   => $vendidos,
                    'disponibles'=> $disponibles,
                    'pct'        => $total > 0 ? round(($vendidos / $total) * 100) : 0,
                    'participo'  => $misNums > 0,
                    'misNums'    => $misNums,
                ];
            });

        return view('cliente.rifas', compact('rifas'));
    }

    public function show(int $id)
    {
        $rifa   = Rifa::where('estado', 'activa')->findOrFail($id);
        $userId = Auth::id();
        $total  = (int) pow(10, $rifa->cifras);

        $numerosDb = $rifa->numeros()->get()->keyBy('numero');

        $numeros = [];
        for ($i = 0; $i < $total; $i++) {
            $key    = str_pad($i, $rifa->cifras, '0', STR_PAD_LEFT);
            $n      = $numerosDb->get($key);
            $estado = $n ? $n->estado : 'disponible';
            $mio    = $n && $n->user_id === $userId;

            $numeros[] = [
                'numero' => $key,
                'estado' => $estado,
                'mio'    => $mio,
            ];
        }

        $vendidos    = collect($numeros)->where('estado', 'vendido')->count();
        $pendientes  = collect($numeros)->where('estado', 'pendiente')->count();
        $disponibles = collect($numeros)->where('estado', 'disponible')->count();
        $misNums     = collect($numeros)->where('mio', true)->count();

        return view('cliente.rifa-detalle', compact(
            'rifa', 'numeros', 'total',
            'vendidos', 'pendientes', 'disponibles', 'misNums'
        ));
    }

    public function reservar(Request $request, int $id, string $numero)
    {
        $rifa = Rifa::where('estado', 'activa')->findOrFail($id);

        $n = NumeroRifa::where('rifa_id', $rifa->id)
                       ->where('numero', $numero)
                       ->firstOrFail();

        if ($n->estado !== 'disponible') {
            return response()->json(['error' => 'Número no disponible.'], 422);
        }

        $n->estado  = 'pendiente';
        $n->user_id = Auth::id();
        $n->save();

        return response()->json(['ok' => true, 'numero' => $numero]);
    }
}
