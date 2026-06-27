<?php

declare(strict_types=1);

namespace Athwari\FilamentZktecoAdms\Tests\Fixtures\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TestUser extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}
