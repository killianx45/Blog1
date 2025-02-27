<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Http\Controllers\AuthController;

#[ApiResource(
    operations: [
        new Post(
            processor: [AuthController::class, 'apiLogin'],
            uriTemplate: '/auth/login'
        )
    ]
)]
class LoginRequest
{
    public string $email;
    public string $password;
}
