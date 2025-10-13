<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 🧾 Listado de usuarios con filtros y búsqueda
     */
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 Filtro de búsqueda (nombre, apellido, correo)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 🎭 Filtro de rol
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // ⚙️ Filtro de estado (si existe el campo)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // ✅ Paginación directa (sin ->get())
        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * 🧍‍♂️ Mostrar formulario de creación
     */
    public function create()
    {
        $roles = UserRole::cases();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * 💾 Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,client,technician',
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => UserRole::from($validated['role']),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * ✏️ Mostrar formulario de edición
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = UserRole::cases();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * 🔄 Actualizar usuario
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
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->email      = $validated['email'];
        $user->role       = UserRole::from($validated['role']);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * 🗑️ Eliminar usuario
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

