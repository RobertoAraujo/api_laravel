<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use App\Service\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
 * @OA\Post(
 *     path="/cadastrar",
 *     summary="Cria um novo usuário",
 *     tags={"Authentication"},
 *     security={{"api_key": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Dados do novo usuário",
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe", description="Nome do usuário"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="E-mail do usuário"),
 *             @OA\Property(property="password", type="string", example="secret", description="Senha do usuário"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuário criado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object", description="Informações do usuário criado"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Não encontrado - rota ou recurso não existe",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", description="Mensagem de erro"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", description="Mensagem de erro"),
 *         )
 *     ),
 * )
 */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = $this->userService->create($request->all());

        return response()->json(['user' => $user], 201);
    }
    
    /**
     * @OA\Post(
     *     path="/logar",
     *     summary="Login",
     *     tags={"Authentication"},
     *     security={{"api_key": {}}},
     *     @OA\RequestBody(
     *         description="User credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="500", description="Internal Server Error"),
     * )
     */
    public function login(Request $request)
    {
        // Realize a autenticação do usuário
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Crie um token para o usuário
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
        }

        // Retorne uma resposta caso a autenticação falhe
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     @OA\Response(response="200", description="List of users"),
     *     security={{"api_key": {}}}
     * )
     */
    public function getUsers()
    {
        $user = $this->userService->getAll();

        return response()->json(['user' => $user], 200);
    }

}