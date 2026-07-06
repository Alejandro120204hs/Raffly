<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\NumeroRifa;
use App\Models\User;
use Illuminate\Http\Request;

class RifasController extends Controller
{
    public function index()
    {
        $rifas = Rifa::orderBy('created_at', 'desc')->get()->map(function ($r) {
            $vendidos = $r->numeros()->where('estado', 'vendido')->count();
            return [
                'id'        => $r->id,
                'nombre'    => $r->nombre,
                'precio'    => $r->precio,
                'vendidos'  => $vendidos,
                'fecha'     => \Carbon\Carbon::parse($r->fecha)->format('d M Y'),
                'estado'    => $r->estado,
                'tipo'      => $r->tipo,
                'premio'    => $r->premio,
                'loteria'   => $r->loteria,
                'cifras'    => (int) $r->cifras,
                'juega'     => $r->juega,
                'resultado' => $r->resultado,
                'total'     => (int) pow(10, $r->cifras),
            ];
        })->toArray();

        return view('admin.rifas.index', compact('rifas'));
    }

    public function create()
    {
        return view('admin.rifas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo'    => 'required|in:objeto,efectivo',
            'premio'  => 'required|string|max:80',
            'cifras'  => 'required|in:2,3,4',
            'precio'  => 'required|integer|min:100',
            'loteria' => 'required|string',
            'juega'   => 'required|string',
            'fecha'   => 'required|date|after:today',
        ]);

        $data['nombre'] = $data['premio'];
        $data['estado'] = 'activa';

        $rifa  = Rifa::create($data);
        $total = (int) pow(10, $rifa->cifras);
        $batch = [];
        for ($i = 0; $i < $total; $i++) {
            $batch[] = [
                'rifa_id'    => $rifa->id,
                'numero'     => str_pad($i, $rifa->cifras, '0', STR_PAD_LEFT),
                'estado'     => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        NumeroRifa::insert($batch);

        return redirect()->route('admin.rifas.index');
    }

    public function show(int $id)
    {
        $db = Rifa::findOrFail($id);

        $numerosDb  = $db->numeros()->with('user')->get()->keyBy('numero');
        $total      = (int) pow(10, $db->cifras);
        $todos      = range(0, $total - 1);
        $vendidos   = $numerosDb->filter(fn($n) => $n->estado === 'vendido')->keys()->map(fn($k) => (int) $k)->values()->toArray();
        $pendientes = $numerosDb->filter(fn($n) => $n->estado === 'pendiente')->keys()->map(fn($k) => (int) $k)->values()->toArray();
        $updateUrl  = route('admin.rifas.numeros.update', ['rifa' => $db->id, 'numero' => '__NUM__']);

        $rifa = [
            'id'        => $db->id,
            'nombre'    => $db->nombre,
            'precio'    => $db->precio,
            'vendidos'  => count($vendidos),
            'fecha'     => \Carbon\Carbon::parse($db->fecha)->format('d M Y'),
            'estado'    => $db->estado,
            'tipo'      => $db->tipo,
            'premio'    => $db->premio,
            'loteria'   => $db->loteria,
            'cifras'    => (int) $db->cifras,
            'juega'     => $db->juega,
            'resultado' => $db->resultado,
            'total'     => $total,
        ];

        $clientes = User::where('role', 'customer')->get(['id','name','email']);

        $ganador = null;
        if ($db->resultado) {
            $numGanador = $numerosDb->get($db->resultado);
            if ($numGanador) {
                if ($numGanador->user_id && $numGanador->user) {
                    $ganador = [
                        'nombre'    => $numGanador->user->name,
                        'celular'   => $numGanador->user->celular,
                        'ubicacion' => ($numGanador->user->municipio && $numGanador->user->departamento)
                            ? $numGanador->user->municipio . ', ' . $numGanador->user->departamento
                            : null,
                        'estado'    => $numGanador->estado,
                        'tipo'      => 'registrado',
                    ];
                } elseif ($numGanador->comprador_nombre) {
                    $ganador = [
                        'nombre'    => trim($numGanador->comprador_nombre . ' ' . $numGanador->comprador_apellido),
                        'celular'   => $numGanador->comprador_celular,
                        'ubicacion' => $numGanador->comprador_ubicacion,
                        'estado'    => $numGanador->estado,
                        'tipo'      => 'externo',
                    ];
                } else {
                    $ganador = ['tipo' => 'sin_comprador', 'estado' => $numGanador->estado];
                }
            }
        }

        $compradores = [];
        foreach ($numerosDb as $numero => $n) {
            if (!in_array($n->estado, ['pendiente', 'vendido'])) continue;
            $nombre = $celular = $ubicacion = null;

            if ($n->user_id && $n->user) {
                $nombre  = $n->user->name;
                $celular = $n->user->celular;
                $ubicacion = ($n->user->municipio && $n->user->departamento)
                    ? $n->user->municipio . ', ' . $n->user->departamento
                    : ($n->user->municipio ?? $n->user->departamento ?? null);
            } elseif ($n->comprador_nombre) {
                $nombre    = trim($n->comprador_nombre . ' ' . $n->comprador_apellido);
                $celular   = $n->comprador_celular;
                $ubicacion = $n->comprador_ubicacion;
            }

            if ($nombre) {
                $compradores[(int)$numero] = [
                    'nombre'    => $nombre,
                    'celular'   => $celular,
                    'ubicacion' => $ubicacion,
                ];
            }
        }

        return view('admin.rifas.show', compact('rifa', 'todos', 'vendidos', 'pendientes', 'updateUrl', 'clientes', 'compradores', 'ganador'));
    }

    public function registrarResultado(Request $request, int $id)
    {
        $rifa = Rifa::findOrFail($id);

        $request->validate([
            'resultado' => ['required', 'regex:/^\d{' . $rifa->cifras . '}$/'],
        ]);

        $rifa->update([
            'resultado' => $request->resultado,
            'estado'    => 'finalizada',
        ]);

        return redirect()->route('admin.rifas.show', $id);
    }

    public function edit(int $id)
    {
        $rifa = Rifa::findOrFail($id);
        return view('admin.rifas.edit', compact('rifa'));
    }

    public function update(Request $request, int $id)
    {
        $rifa = Rifa::findOrFail($id);

        $data = $request->validate([
            'tipo'    => 'required|in:objeto,efectivo',
            'premio'  => 'required|string|max:80',
            'precio'  => 'required|integer|min:100',
            'loteria' => 'required|string',
            'juega'   => 'required|string',
            'fecha'   => 'required|date',
        ]);

        $data['nombre'] = $data['premio'];
        $rifa->update($data);

        return redirect()->route('admin.rifas.index');
    }

    public function finalizar(int $id)
    {
        Rifa::findOrFail($id)->update(['estado' => 'finalizada']);
        return redirect()->route('admin.rifas.index');
    }

    public function updateNumero(Request $request, int $rifaId, string $numero)
    {
        $request->validate([
            'estado'              => 'required|in:disponible,pendiente,vendido',
            'user_id'             => 'nullable|exists:users,id',
            'comprador_nombre'    => 'nullable|string|max:80',
            'comprador_apellido'  => 'nullable|string|max:80',
            'comprador_ubicacion' => 'nullable|string|max:120',
            'comprador_celular'   => 'nullable|string|max:20',
        ]);

        $rifa = Rifa::findOrFail($rifaId);

        if ($rifa->numeros()->count() === 0) {
            $total = (int) pow(10, $rifa->cifras);
            $batch = [];
            for ($i = 0; $i < $total; $i++) {
                $batch[] = [
                    'rifa_id'    => $rifa->id,
                    'numero'     => str_pad($i, $rifa->cifras, '0', STR_PAD_LEFT),
                    'estado'     => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            NumeroRifa::insert($batch);
        }

        $update = ['estado' => $request->estado];

        if ($request->estado === 'disponible') {
            $update += [
                'user_id' => null, 'comprador_nombre' => null,
                'comprador_apellido' => null, 'comprador_ubicacion' => null,
                'comprador_celular' => null,
            ];
        } else {
            $update += [
                'user_id'             => $request->user_id,
                'comprador_nombre'    => $request->comprador_nombre,
                'comprador_apellido'  => $request->comprador_apellido,
                'comprador_ubicacion' => $request->comprador_ubicacion,
                'comprador_celular'   => $request->comprador_celular,
            ];
        }

        NumeroRifa::where('rifa_id', $rifaId)
            ->where('numero', $numero)
            ->update($update);

        return response()->json(['ok' => true]);
    }
}
