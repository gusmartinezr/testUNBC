<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nro_phone' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            // La validación ha fallado, haz algo aquí, como redireccionar con mensajes de error
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crea el usuario en la base de datos
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'nro_phone' => $request->nro_phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        // Redirecciona a alguna página después de crear el usuario
        return redirect()->route('dashboard.index')->with('success', 'El usuario se ha registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = $id;
        $user = User::findOrFail($userId); // Obtener el usuario por su ID
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Buscar el usuario que se quiere actualizar
        $user = User::findOrFail($id);

        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nro_phone' => ['required'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            // La validación ha fallado, haz algo aquí, como redireccionar con mensajes de error
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Actualizar los datos del usuario
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->nro_phone = $request->nro_phone;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Redirecciona a alguna página después de actualizar el usuario
        return redirect()->route('dashboard.index')->with('success', 'El usuario se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('dashboard.index')->with('success', 'El usuario se ha eliminado correctamente.');
    }
}
