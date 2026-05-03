<?php

namespace App\Models;

use App\Traits\WithData;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use WithData;
}
