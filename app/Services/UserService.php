<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Models\UserModel as User;
use App\Services\Interfaces\UserServiceInterface;
use App\Helpers\LogHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService implements UserServiceInterface
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * Este método recibe un objeto `PatientsData` con los datos del Usuario,
     * crea un nuevo modelo `Usuario`, guarda los datos en la base de datos
     * dentro de una transacción y devuelve el modelo `Usuario` creado.
     * En caso de error, realiza un rollback de la transacción y re-lanza la excepción.
     *
     * @param PatientsData $UsuarioData Los datos del Usuario a crear.
     * @return Usuario El modelo `Usuario` creado.
     * @throws Throwable Si ocurre un error durante la creación del Usuario.
     */
    public function create(UserData $userData)
    {

        try 
        {
            DB::beginTransaction();

            return User::create([
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'email' => $userData->email,
                'password' => Hash::make($userData->password),
                'date_of_birth' => $userData->date_of_birth,
                'gender' => $userData->gender,
                'address' => $userData->address,
                'phone_number' => $userData->phone_number,
                'email' => $userData->email,
                'insurance' => $userData->insurance,
                'dni' => $userData->dni,
                'product' => $userData->product, 
                'membership' => $userData->membership

            ]);

            DB::commit();

            $this->logService->logInfo(LogHelpers::InfoCreate(), ['context' => LogHelpers::CreateUser()]);
        } 
        catch (Throwable $e) 
        {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }
}
