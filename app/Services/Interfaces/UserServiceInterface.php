<?php

namespace App\Services\Interfaces;

use App\DataTransferObjects\UserData;

interface UserServiceInterface
{
    /**
     * Crea un nuevo usuario en el sistema
     *
     * @param UserData $userData
     * @return mixed
     */
    public function create(UserData $userData);


    /**
     * Actualiza un usuario existente en el sistema
     *
     * @param int $userId
     * @param UserData $userData
     * @return mixed
     */
    //public function update(int $userId, UserData $userData);

    /**
     * Elimina un usuario del sistema
     *
     * @param int $userId
     * @return mixed
     */
    //public function delete(int $userId);
}