<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;


#[MapInputName(SnakeCaseMapper::class)]
class LogData extends Data
{

    public string $context;
    public string $level;
    public string $message;
}
