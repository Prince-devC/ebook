<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        // Vérifier l'inactivité (30 minutes)
        $lastActivity = $request->session()->get('admin_last_activity');
        if ($lastActivity && (time() - $lastActivity) > 1800) { // 1800 secondes = 30 minutes
            $request->session()->forget(['admin_authenticated', 'admin_last_activity']);
            return redirect()->route('admin.login')->with('error', 'Session expirée après 30 minutes d\'inactivité');
        }

        // Mettre à jour le timestamp d'activité
        $request->session()->put('admin_last_activity', time());

        return $next($request);
    }
}
