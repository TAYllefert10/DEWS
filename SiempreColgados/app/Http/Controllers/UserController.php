<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('empleado');

        // Filtros opcionales
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->buscar}%")
                    ->orWhere('email', 'like', "%{$request->buscar}%");
            });
        }

        if ($request->filled('auth_method')) {
            $query->withAuthMethod($request->auth_method);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        // Obtener empleados que no estén ya vinculados a un usuario
        $empleados = Empleado::whereDoesntHave('user')
            ->orWhere('user_id', $usuario->id)
            ->get();

        return view('admin.usuarios.edit', compact('usuario', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required|in:operario,admin,administrador',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El formato del email no es válido',
            'role.required' => 'Debe seleccionar un rol',
        ]);

        try {
            $usuario->update($request->only(['name', 'email', 'role']));

            return redirect()->route('usuarios.show', $usuario)
                ->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'No se pudo actualizar el usuario.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Seguridad: no permitir eliminar el propio usuario
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Seguridad: no eliminar si es el último administrador
        if ($usuario->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Debe haber al menos un administrador en el sistema.');
        }

        try {
            // Eliminar relación con empleado si existe
            if ($usuario->empleado) {
                $usuario->empleado->update(['user_id' => null]);
            }

            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el usuario.');
        }
    }

    /**
     * Vincular usuario con un registro de Empleado existente.
     */
    public function vincularEmpleado(Request $request, User $usuario)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id'
        ]);

        try {
            $empleado = Empleado::findOrFail($request->empleado_id);

            // Si el empleado ya tiene usuario, desvincularlo primero
            if ($empleado->user_id && $empleado->user_id !== $usuario->id) {
                $oldUser = User::find($empleado->user_id);
                if ($oldUser) {
                    $oldUser->empleado()->dissociate();
                    $oldUser->save();
                }
            }

            // Vincular nuevo usuario
            $empleado->update(['user_id' => $usuario->id]);

            // Opcional: sincronizar datos básicos si están vacíos en el usuario
            if (empty($usuario->name) && !empty($empleado->nombre)) {
                $usuario->name = $empleado->nombre;
            }
            if (empty($usuario->email) && !empty($empleado->correo)) {
                $usuario->email = $empleado->correo;
            }
            $usuario->save();

            return back()->with('success', '✅ Usuario vinculado al empleado "' . $empleado->nombre . '" correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al vincular usuario con empleado: ' . $e->getMessage());
            return back()->with('error', 'No se pudo vincular el usuario con el empleado.');
        }
    }
}
