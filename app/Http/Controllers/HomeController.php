<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderReceivedNotification;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $brand = 'Bagus Setiawan';

        $services = Service::published()->orderBy('sort_order')->get();

        $latestProjects = Project::published()
            ->forLatest()
            ->orderBy('sort_order')
            ->limit(2) // kalau mau tampil 2 saja (sesuai layout latest)
            ->get();

        $portfolioProjects = Project::published()
            ->forPortfolio()
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::published()->orderBy('sort_order')->get();

        return view('index', compact(
            'brand', 'services', 'latestProjects', 'portfolioProjects', 'testimonials'
        ));
    }

    public function storeOrder(Request $request)
    {
        if ($request->filled('website')) return back();

        $data = $request->validate([
            'type' => ['required', 'in:order,consultation'],

            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'whatsapp' => ['nullable', 'string', 'max:30'],

            'service' => ['nullable', 'string', 'max:120'],
            'topic' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],

            'budget_range' => ['nullable', 'string', 'max:50'],
            'deadline' => ['nullable', 'date'],

            'preferred_channel' => ['nullable', 'string', 'max:50'],
            'preferred_time' => ['nullable', 'string', 'max:80'],
        ]);

        $data['status'] = 'new';
        $order = Order::create($data);

        // kirim email notif ke admin
        $adminEmail = config('app.admin_notify_email');
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new OrderReceivedNotification($order));
        }

        $data['status'] = 'new';

        Order::create($data);

        return back()->with('order_success', 'Order berhasil dikirim. Saya akan balas secepatnya 🙌');
    }
}
