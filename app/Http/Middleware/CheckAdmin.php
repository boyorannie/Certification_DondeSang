<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.  
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est un admin
        if (auth()->check() && auth()->user()->role->id === 1) {
            return $next($request);
        }

        // Si ce n'est pas un admin, renvoyer une réponse non autorisée
        return response()->json([
            "status" => false,
            "message" => "Vous n'avez pas les autorisations nécessaires pour effectuer cette action."
        ], 403);
    }
}
