<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\LogData;
use App\Http\Controllers\Controller;
use App\Services\LogService;
use App\Http\Requests\StoreLogRequest;
use Illuminate\Http\Request;

class LogControllers extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Maneja la solicitud para crear un nuevo registro de log.
     *
     * Este método recibe una solicitud validada mediante `StoreLogRequest`,
     * convierte los datos a un DTO `LogData`, y llama al servicio `LogService`
     * para crear el log en el sistema.
     *
     * @param StoreLogRequest $request La solicitud validada que contiene los datos del log.
     * @return void
     * @throws Throwable Si ocurre un error durante el proceso.
     */
    public function someMethod(StoreLogRequest $request)
    {
        try {
            $data = $request->validated();

            $this->logService->logInfo($data['message'], $data['context'] ?? []);

            return response()->json(['message' => 'Datos registrados correctamente'], 200);
        } catch (\Exception $e) {
            $this->logService->logError('Error al realizar la operación', [
                'error' => $e->getMessage(),
                //'user_id' => $request->user()->id,
            ]);
            return response()->json(['message' => 'Error al realizar la operación'], 500);
        }
    }
}
