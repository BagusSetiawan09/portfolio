<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'website' => ['nullable'], // honeypot
            'type' => ['required', 'in:order,consultation'],

            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'service' => ['nullable', 'string', 'max:120'],

            'topic' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],

            // order
            'budget_range' => ['nullable', 'string', 'max:80'],
            'deadline' => ['nullable', 'date'],

            // consultation
            'preferred_channel' => ['nullable', 'string', 'max:50'],
            'preferred_time' => ['nullable', 'string', 'max:80'],
        ]);

        if (!empty($validated['website'])) {
            abort(422);
        }
        unset($validated['website']);

        $validated['status'] = 'new';

        Order::create($validated);

        return back()->with('order_success', 'Terima kasih! Pesan kamu sudah masuk. Saya akan balas secepatnya.');
    }
}
