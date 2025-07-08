<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер аутентификации для API.
 *
 * Обрабатывает вход и выход пользователей через токены Sanctum.
 */
class AuthController extends ApiController
{
    /**
     * Авторизация пользователя.
     *
     * При успешной проверке возвращает access token для использования в API.
     *
     * @param Request $request HTTP-запрос с полями `email` и `password`
     * @return JsonResponse
     *
     * @response 200 {
     *   "data": {
     *     "token": "..."
     *   }
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->responseWithData([
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }

    /**
     * Выход пользователя из системы.
     *
     * Удаляет текущий access token пользователя (разлогинивает только текущую сессию).
     *
     * @param Request $request HTTP-запрос с аутентифицированным пользователем
     * @return JsonResponse
     *
     * @response 200 {
     *   "message": "Logged out successfully."
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseMessage('Logged out successfully.');
    }
}
