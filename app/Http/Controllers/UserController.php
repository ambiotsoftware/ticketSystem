<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * �0�8 Listado de usuarios con filtros y b��squeda
     */
    public function index(Request $request)
    {
        $query = User::query();

        // �9�3 Filtro de b��squeda (nombre, apellido, correo)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // �9�1 Filtro de rol
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // �7�5�1�5 Filtro de estado (si existe el campo)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // �7�3 Paginaci��n directa (sin ->get())
        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * �0�9�6�9��1�5 Mostrar formulario de creaci��n
     */
    public function create()
    {
        $roles = UserRole::cases();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * �9�4 Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,client,technician',
            'company_name'  => [
                Rule::requiredIf($request->role === 'client'),
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => UserRole::from($validated['role']),
            'company_name' => $validated['company_name'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * �7�3�1�5 Mostrar formulario de edici��n
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = UserRole::cases();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * �9�4 Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6',
            'role'       => 'required|in:admin,client,technician',
            'company_name'  => [
                Rule::requiredIf($request->role === 'client'),
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->email      = $validated['email'];
        $user->role       = UserRole::from($validated['role']);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->company_name = $validated['company_name'] ?? null;

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * �9�9�1�5 Eliminar usuario
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

