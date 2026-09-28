<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureClienteLogado
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');

        $tenant = User::where('slug', $slug)->firstOrFail();

        $cliente = Auth::guard('cliente')->user();

        if (! $cliente || (int) $cliente->tenant_id !== (int) $tenant->id) {
            // Guarda a página que ele tentou abrir para voltar depois do login
            if ($request->isMethod('GET')) {
                session()->put('cliente.intended', $request->fullUrl());
            }

            return redirect('/' . $slug . '?login=1');
        }

        return $next($request);
    }
}
