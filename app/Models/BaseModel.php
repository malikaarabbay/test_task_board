<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Базовая модель BaseModel.
 *
 * Предоставляет общее поведение для всех моделей проекта, включая форматирование даты.
 *
 */
class BaseModel extends Model
{
    /**
     * Переопределение форматирования даты при сериализации в JSON.
     *
     * Устанавливает часовой пояс Asia/Almaty и формат `Y-m-d H:i:s`.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->setTimezone('Asia/Almaty')->format('Y-m-d H:i:s');
    }
}
