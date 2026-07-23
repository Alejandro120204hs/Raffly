<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        return view('cliente.perfil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'celular'      => ['nullable', 'string', 'max:20'],
            'departamento' => ['nullable', 'string', 'max:100'],
            'municipio'    => ['nullable', 'string', 'max:100'],
        ]);

        $user->name         = $validated['name'];
        $user->celular      = $validated['celular'] ?? null;
        $user->departamento = $validated['departamento'] ?? null;
        $user->municipio    = $validated['municipio'] ?? null;
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
