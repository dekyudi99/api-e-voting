<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Table('access_code')]
#[Fillable(['code', 'is_used', 'used_at'])]
class AccessCode extends Model
{
    //
}
