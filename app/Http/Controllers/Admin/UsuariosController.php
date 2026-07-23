<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NumeroRifa;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($u) {
                $boletas = NumeroRifa::where('user_id', $u->id)
                    ->selectRaw("
                        SUM(CASE WHEN estado = 'vendido'   THEN 1 ELSE 0 END) as vendidas,
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
                    ")
                    ->first();

                $totalGastado = DB::table('numeros_rifa')
                    ->join('rifas', 'numeros_rifa.rifa_id', '=', 'rifas.id')
                    ->where('numeros_rifa.user_id', $u->id)
                    ->where('numeros_rifa.estado', 'vendido')
                    ->sum('rifas.precio');

                $gano = DB::table('rifas')
                    ->join('numeros_rifa', function ($join) use ($u) {
                        $join->on('rifas.id', '=', 'numeros_rifa.rifa_id')
                             ->on('rifas.resultado', '=', 'numeros_rifa.numero')
                             ->where('numeros_rifa.user_id', $u->id);
                    })
                    ->whereNotNull('rifas.resultado')
                    ->exists();

                return [
                    'id'            => $u->id,
                    'name'          => $u->name,
                    'email'         => $u->email,
                    'celular'       => $u->celular,
                    'departamento'  => $u->departamento,
                    'municipio'     => $u->municipio,
                    'vendidas'      => (int) ($boletas->vendidas   ?? 0),
                    'pendientes'    => (int) ($boletas->pendientes ?? 0),
                    'total_gastado' => (int) $totalGastado,
                    'gano'          => $gano,
                    'miembro_desde' => $u->created_at->format('d M Y'),
                ];
            });

        return view('admin.usuarios.index', compact('usuarios'));
    }
}
