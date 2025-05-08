<?php

namespace App\Helpers;

/**
 * Enumeración que define los mensajes para diferentes servicios y métodos.
 *
 * Esta enumeración proporciona cadenas de texto predefinidas para identificar
 * servicios y el estado de la ejecución de métodos.  Su uso principal es para
 * facilitar el registro de logs y proporcionar mensajes consistentes en la
 * aplicación.
 */
enum LogHelpers: string
{

    /**
     * Obtiene el valor string del caso del enum.
     *
     * Este método permite acceder al string asociado a un caso específico
     * del enum.
     *
     * @return string El valor string del caso.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Método estático para acceder a los casos del enum como si fueran métodos.
     *
     * Este método mágico permite llamar a los casos del enum directamente, por
     * ejemplo: `LogHelpers::CreateUser()`.
     *
     * @param string $name El nombre del caso al que se intenta acceder.
     * @param array $arguments Argumentos (no se usan en este caso).
     * @return string El valor string del caso.
     * @throws \BadMethodCallException Si el caso no existe.
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (defined("self::$name")) {
            $case = constant("self::$name");
            if ($case instanceof self) {
                return $case->getValue();
            }
        }

        throw new \BadMethodCallException("No such case: $name");
    }


    /**
     * Mensaje para indicar que el método Get se ejecutó satisfactoriamente.
     */
    case InfoGet = 'Método Get ejecutado satisfactoriamente';

    /**
     * Mensaje para indicar que el método Create se ejecutó satisfactoriamente.
     */
    case InfoCreate = 'Método Create ejecutado satisfactoriamente';

    /**
     * Mensaje para indicar que el método Update se ejecutó satisfactoriamente.
     */
    case InfoUpdate = 'Método Update ejecutado satisfactoriamente';

    /**
     * Mensaje para indicar que el método PATCH se ejecutó satisfactoriamente.
     */
    case InfoPatch = 'Método PATCH ejecutado satisfactoriamente';

    /**
     * Mensaje para indicar que el método Delete se ejecutó satisfactoriamente.
     */
    case InfoDelete = 'Método Delete ejecutado satisfactoriamente';
    
    /**
     * Mensaje para el servicio de creación de Usuario.
     */
    case CreateUser = 'Servicio CreateUser';

    /**
     * Mensaje para el servicio de Listado de Usuario.
     */
    case GetAllUser = 'Servicio GetAllUser';
    /**
     * Mensaje para el servicio de Listado de Usuario por ID.
     */
    case GetPatientById = 'Servicio GetPatientById';

}