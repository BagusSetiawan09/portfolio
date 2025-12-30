<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $today = now()->toDateString();

        $visitor = Visitor::where('ip_address', $ip)
            ->where('visit_date', $today)
            ->first();

        if ($visitor) {

            $visitor->touch(); 

        } else {
            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'visit_date' => $today,
            ]);
        }

        return $next($request);
    }
}