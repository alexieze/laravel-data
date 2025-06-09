<?php

namespace Spatie\LaravelData\Tests\Fakes;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public string $username;
    public string $email;
    public ?string $bio = null;
    public ?string $website = null;
    public ?string $avatar = null;
}
