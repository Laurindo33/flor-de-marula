<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeSuperAdmin();

        return view('admin.users.index', [
            'admins' => Admin::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:Super Admin,Gestor,Atendimento,Marketing'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return back()->with('admin_success', 'Utilizador criado com sucesso.');
    }

    public function destroy(Admin $user): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        abort_if($user->id === Auth::guard('admin')->id(), 403, 'Não pode eliminar a sua própria conta.');

        $user->delete();

        return back()->with('admin_success', 'Utilizador removido.');
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless(Auth::guard('admin')->user()->isSuperAdmin(), 403);
    }
}
