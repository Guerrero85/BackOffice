<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
    * Almacena un nuevo paciente en la base de datos.
    *
    * Este método recibe una solicitud validada (`StorePatientsRequest`) con los datos del paciente,
    * utiliza un servicio (`pacienteService`) para crear el paciente y devuelve una respuesta JSON.
    *
    * @param StoreUserRequest $request La solicitud con los datos del paciente validados.
    * @return \Illuminate\Http\JsonResponse Una respuesta JSON con el resultado de la operación.
    */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            // Crear el DTO a partir de la solicitud (usar from para llenar el DTO)
            $userData = UserData::from($request->validated());
            $user = $this->userService->create($userData);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user,
            ], 201);
        } 
        catch (Throwable $e) 
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage(), // Opcional: solo en desarrollo
            ], 500);
        }
    }
}
