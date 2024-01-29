<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserService;
use Illuminate\Support\Facades\Auth;

class TokenAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
   /**
     * Class Controller
     * @package App\Http\Controllers
     *
     * @OA\Info(
     *     title="API USER",
     *     version="1.0",
     *     description="API REST do serviço da base USER."
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="Token",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Autenticação baseada em token. Inclua o token JWT no cabeçalho 'Authorization' com o prefixo 'Bearer'.",
     * )
     *
     * @OA\Server(
     *     url="http://localhost:8000/api/user",
     *     description="Caminho base da API"
     * )
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rotas permitidas para Swagger
        $allowedRoutes = [
            '/api_user/docs',            
            '/api_user/docs/api-docs.json', 
        ];

        $currentRoute = trim(parse_url($request->url(), PHP_URL_PATH), '/');

        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        $response = app('auth')->guard('sanctum')->check()
            ? $next($request)
            : response()->json(['message' => 'Não autorizado'], 401);

        return $response;
    }

}
