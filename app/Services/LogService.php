<?php

namespace App\Services;

use App\DataTransferObjects\LogData;
use App\Models\LogModel; 
use App\Services\Interfaces\LogServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Throwable;

class LogService implements LogServiceInterface
{

    /**
    * Registra un mensaje de log con nivel de información.
    *
    * Este método recibe un mensaje y un contexto opcional, 
    * registra el mensaje en el sistema de logging de Laravel 
    * y guarda el registro en la base de datos.
    *
    * @param string $message El mensaje del log.
    * @param array $context Contexto adicional que se puede incluir en el log.
    * @return void
    * @throws Throwable Si ocurre un error durante el proceso de guardado.
    */
    public function logInfo($message, array $context = [])
    {
        Log::info($message, $context);
        $this->saveLog('info', $message, $context);
    }

    /**
    * Registra un mensaje de log con nivel de advertencia.
    *
    * Este método recibe un mensaje y un contexto opcional, 
    * registra el mensaje en el sistema de logging de Laravel 
    * y guarda el registro en la base de datos.
    *
    * @param string $message El mensaje del log.
    * @param array $context Contexto adicional que se puede incluir en el log.
    * @return void
    * @throws Throwable Si ocurre un error durante el proceso de guardado.
    */
    public function logWarning($message, array $context = [])
    {
        Log::warning($message, $context);
        $this->saveLog('warning', $message, $context);
    }

    /**
    * Registra un mensaje de log con nivel de error.
    *
    * Este método recibe un mensaje y un contexto opcional, 
    * registra el mensaje en el sistema de logging de Laravel 
    * y guarda el registro en la base de datos.
    *
    * @param string $message El mensaje del log.
    * @param array $context Contexto adicional que se puede incluir en el log.
    * @return void
    * @throws Throwable Si ocurre un error durante el proceso de guardado.
     */
    public function logError($message, array $context = [])
    {
        Log::error($message, $context);
        $this->saveLog('error', $message, $context);
    }

    /**
    * Guarda un registro de log en la base de datos.
    *
    * Este método se encarga de crear un nuevo registro en la base de datos 
    * con el nivel, mensaje y contexto proporcionados.
    *
    * @param string $level El nivel de log (e.g., info, warning, error).
    * @param string $message El mensaje del log.
    * @param array $context Contexto adicional que se puede incluir en el log.
    * @return void
    * @throws Throwable Si ocurre un error durante el proceso de guardado.
    */
    protected function saveLog($level, $message, array $context = [])
    {
        LogModel::create([
            'level' => $level,
            'message' => $message,
            'context' => json_encode($context), // Convertir el contexto a JSON
        ]);
    }

}