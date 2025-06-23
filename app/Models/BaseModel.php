<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
    /**
     * Переопределяем сериализацию даты для JSON.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->setTimezone('Asia/Almaty')->format('Y-m-d H:i:s');
    }
}
