<?php

namespace App\Helpers;

enum Version: string {
    case V1 = 'v1';
    case V2 = 'v2';
}

class VersionHelper
{
    /**
     * Devuelve la versión por defecto de la API.
     */
    public static function defaultVersion(): Version
    {
        return Version::V1;
    }

    /**
     * Valida si una versión dada es válida.
     */
    public static function isValidVersion(string $version): bool
    {
        try
        {
            Version::from($version);
            return true;
        } 
        catch (\ValueError $e) {
            return false;
        }
    }

    /**
     * Obtiene un array con todas las versiones disponibles (como strings).
     */
    public static function getAvailableVersions(): array
    {
        return array_map(fn(Version $case) => $case->value, Version::cases());
    }

    /**
     * Genera el prefijo de ruta para una versión.
     */
    public static function routePrefix(Version|string|null $version = null): string
    {
        if ($version instanceof Version) 
        {
            $versionString = $version->value;
        } 
        elseif (is_string($version)) 
        {
            if (!self::isValidVersion($version)) 
            {
                throw new \InvalidArgumentException("Version {$version} is not valid");
            }
            $versionString = $version;
        } 
        else 
        {
            $versionString = self::defaultVersion()->value;
        }

        return $versionString;
    }

    /**
     * Convierte un string a un caso del enum Version.
     */
    public static function fromString(string $version): Version
    {
        return Version::from($version);
    }

    /**
     * Convierte un string a un caso del enum Version de forma segura.
     */
    public static function tryFromString(string $version): ?Version
    {
        return Version::tryFrom($version);
    }
}