<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
    * Store a newly created user in storage.
    */
    public function store(StoreUserRequest $request): JsonResponse
    {

        // Crear el DTO a partir de la solicitud (usar from para llenar el DTO)
        $userData = UserData::from($request->validated());

        $user = $this->userService->create($userData);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
}