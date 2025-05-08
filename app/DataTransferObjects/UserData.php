<?php

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
    public ?string $gender; 
    public ?string $address;
    public ?string $phone_number;
    public int $insurance;
    public ?string $dni;
    public int $product;
    public ?string $membership;
}