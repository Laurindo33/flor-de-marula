<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index', [
            'customers' => User::withCount('orders')->latest()->paginate(20),
        ]);
    }

    public function show(User $customer)
    {
        $customer->load(['orders', 'addresses']);

        return view('admin.customers.show', compact('customer'));
    }
}
