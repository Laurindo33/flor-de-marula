<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('account.index', [
            'user' => $user,
            'recentOrders' => $user->orders()->take(3)->get(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($validated);

        return back()->with('account_success', 'Perfil atualizado com sucesso.');
    }

    public function orders()
    {
        return view('account.orders', [
            'orders' => Auth::user()->orders,
        ]);
    }

    public function orderShow(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items');

        return view('account.order-detail', compact('order'));
    }

    public function addresses()
    {
        return view('account.addresses', [
            'addresses' => Auth::user()->addresses,
        ]);
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
        ]);

        Auth::user()->addresses()->create($validated);

        return back()->with('account_success', 'Endereço adicionado.');
    }

    public function destroyAddress(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $address->delete();

        return back()->with('account_success', 'Endereço removido.');
    }

    public function favorites()
    {
        return view('account.favorites', [
            'favorites' => Auth::user()->favorites()->with('product')->get(),
        ]);
    }

    public function reviews()
    {
        return view('account.reviews', [
            'reviews' => Auth::user()->reviews()->with('product')->get(),
        ]);
    }
}
