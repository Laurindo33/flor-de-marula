<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $order->load('items');

        return view('order.confirmation', compact('order'));
    }
}
