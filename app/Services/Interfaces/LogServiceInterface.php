<?php

namespace App\Services\Interfaces;

use App\Models\Log as Logservice;
use App\DataTransferObjects\LogData;
use Illuminate\Database\Eloquent\Collection;

interface LogServiceInterface
{   
    public function logInfo($message, array $context = []);
    public function logWarning($message, array $context = []);
    public function logError($message, array $context = []);
}