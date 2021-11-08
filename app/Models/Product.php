<?php

namespace App\Models;

use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function getUser()
    {
        return $this->hasOne(User::class, Constants::ID, Constants::USER_ID);
    }
}
