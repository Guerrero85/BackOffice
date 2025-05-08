# Arquitectura

(Clean Architecture) es un patrón de diseño de software que busca crear sistemas altamente mantenibles, testeables y escalables. Se centra en la separación de responsabilidades y la creación de capas de abstracción que aíslan el código de los detalles de implementación, como frameworks, bases de datos o interfaces de usuario.

> "Esta estructura de proyecto implementa los principios de la Arquitectura Limpia, buscando la separación de responsabilidades y la alta mantenibilidad."
# 1. Controladores

**Ubicación:** `app/Http/Controllers/` (para API: `app/Http/Controllers/Api/`)

**Nombre:** En PascalCase y singular (ej: `UserController`, `LogController`).

**Métodos comunes:**

- `index()`: Listar recursos (GET).
- `store()`: Crear recurso (POST).
- `show()`: Mostrar un recurso (GET).
- `update()`: Actualizar recurso (PUT/PATCH).
- `destroy()`: Eliminar recurso (DELETE).

**Convenciones:**

- Usar inyección de dependencias (ej: `LogService $service`).
- Mantenerlos ligeros (delegar lógica a Servicios o Repositorios).
- Para APIs, retornar respuestas JSON consistentes (usar `response()->json()`).

### Ejemplo: `LogController`

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LogService;
use App\DataTransferObjects\LogData;
use Illuminate\Http\JsonResponse;

class LogController extends Controller
{
    public function __construct(private LogService $service) {}

    public function index(): JsonResponse
    {
        $Logs = $this->service->getAll();
        return response()->json($Logs);
    }
}

```
# 2. Modelos
**Ubicación:** `app/Models/`

**Nombre:** En PascalCase y singular (ej: `User`, `User`).

**Convenciones:**

- Usar `SoftDeletes` si aplica (use `SoftDeletes`).

- Definir `$fillable` o `$guarded` para asignación masiva.

- Relaciones: nombres descriptivos en snake_case (ej: `appointments()`).

Usar scopes para consultas reutilizables.

## Ejemplo: `UserModel`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'birth_date'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

```

# 3. DTOs (Data Transfer Objects)

**Ubicación:** `app/DataTransferObjects/`

**Nombre:** En PascalCase + sufijo `Data` (ej: `UserData`).

## Convenciones:

- Usar propiedades públicas o readonly (PHP 8.2+).
- Inmutables (una vez creados, no cambiar valores).
- Pueden incluir validación básica o transformación de datos.

## Ejemplo: `UserData`

```php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;


#[MapInputName(SnakeCaseMapper::class)]
class UserData extends Data
{   
    public string $first_name; 
    public string $last_name;
    public string $email;
    public string $password;
    public ?string $date_of_birth;

}

// Para mayor Informacion del SnakeCaseMapper::class vaya a la carpeta Markdown/Class/SnakeCaseMapper.md

```
# 4. Servicios

**Ubicación:** `app/Services/` (interfaces en `app/Services/Interfaces/`).

**Nombre:** En PascalCase + sufijo `Service` (ej: `UserService`).

**Interfaces:** Mismo nombre + sufijo `Interface` (ej: `UserServiceInterface`).

## Convenciones:

- Contener lógica de negocio (no de acceso a datos).
- Usar inyección de dependencias (ej: inyectar modelos o repositorios).
- Métodos descriptivos (ej: `create`, `cancelAppointment`).

## Ejemplo: `UserService`

```php
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
     * Este método recibe un objeto `User Data` con los datos del Usuario,
     * crea un nuevo modelo `Usuario`, guarda los datos en la base de datos
     * dentro de una transacción y devuelve el modelo `Usuario` creado.
     * En caso de error, realiza un rollback de la transacción y re-lanza la excepción.
     *
     * @param UserData $userData Los datos del Usuario a crear.
     * @return User El modelo `Usuario` creado.
     * @throws Throwable Si ocurre un error durante la creación del Usuario.
     */
    public function create(UserData $userData)
    {
        try 
        {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'email' => $userData->email,
                'password' => Hash::make($userData->password),
                'date_of_birth' => $userData->date_of_birth,
            ]);

            DB::commit();

            $this->logService->logInfo(LogHelpers::InfoCreate(), ['context' => LogHelpers::CreateUser()]);

            return $user;
        } 
        catch (Throwable $e) 
        {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }
}

// Para mayor Informacion del DB::Transaction() vaya a la carpeta Markdown/Class/Transaction.md
```

# 5. Interfaces de Servicios

**Ubicación:** `app/Services/Interfaces/`

**Nombre:** Igual que el servicio + sufijo `Interface` (ej: `User ServiceInterface`).

## Convenciones:

- Definir métodos públicos del servicio.
- Usar type-hinting claro (DTOs, modelos, etc.).

## Ejemplo: `UserServiceInterface`

```php
namespace App\Services\Interfaces;

use App\DataTransferObjects\UserData;

interface UserServiceInterface
{
    /**
     * Crea un nuevo usuario en el sistema.
     *
     * @param UserData $userData Los datos del usuario a crear.
     * @return mixed El resultado de la operación de creación.
     */
    public function create(UserData $userData);
}
```

## Beneficios de Usar Interfaces

- **Abstracción:** Permite definir un contrato que las implementaciones deben seguir, facilitando la separación de la lógica de negocio.
- **Flexibilidad:** Facilita el cambio de implementaciones sin afectar el código que depende de la interfaz.
- **Testabilidad:** Mejora la capacidad de realizar pruebas unitarias al permitir el uso de mocks o stubs.

## Conclusión


Esta documentación proporciona una estructura clara sobre las interfaces de servicios, incluyendo un ejemplo de código y una explicación de los beneficios de su uso.

# 6- Form Requests (Validación)

**Ubicación:** `app/Http/Requests/`

**Nombre:** En PascalCase + sufijo `Request` (ej: `StoreUserRequest`).

## Convenciones:

- Usar `authorize()` para permisos.
- Reglas en `rules()` (pueden reutilizarse con `Rule::make()`).
- Mensajes personalizados en `messages()`.

## Ejemplo: `StoreUserRequest`

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permiso para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Cambiar a lógica de permisos.
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:Users',
        ];
    }
}

```

## Beneficios de Usar Form Requests

- **Validación Centralizada:** Permite centralizar la validación de las solicitudes en un solo lugar, lo que facilita la gestión y el mantenimiento del código.

- **Reutilización de Reglas:** Las reglas de validación pueden reutilizarse en diferentes solicitudes, lo que reduce la duplicidad de código.

- **Mensajes Personalizados:** Permite definir mensajes personalizados para cada regla de validación, lo que mejora la experiencia del usuario.

# Reglas Generales de Codificación

## PSR-12
Seguir estándares de codificación PHP según las especificaciones PSR-12.

## Type-hinting
- Utilizar tipado estricto siempre que sea posible (PHP 8.3+)
- Especificar tipos para parámetros y valores de retorno
- Usar declaraciones `declare(strict_types=1)`

## Principio de Responsabilidad Única (Single Responsibility)
- Cada clase debe tener una única responsabilidad
- Evitar clases "DI" que hagan demasiadas cosas
- Dividir lógica compleja en múltiples clases/interfaces

## Inyección de Dependencias
- Evitar instanciación directa con `new Class()`
- Utilizar inyección de dependencias a través del constructor
- Aprovechar el contenedor de servicios de Laravel
- Preferir interfaces sobre implementaciones concretas

## Namespaces
- Los namespaces deben coincidir exactamente con la estructura de directorios
- Seguir convenciones PSR-4 para autoloading
- Usar namespaces jerárquicos según la organización del proyecto


# Estructura Final Recomendada:
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── PatientController.php
│   └── Requests/
│       └── StorePatientRequest.php
├── Models/
│   └── Patient.php
├── Services/
│   ├── Interfaces/
│   │   └── PatientServiceInterface.php
│   └── PatientService.php
└── DataTransferObjects/
    └── PatientData.php

```