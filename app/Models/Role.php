<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель Role.
 *
 * Представляет роль пользователя в системе (например: администратор, модератор, пользователь).
 *
 * @property int $id
 * @property string $name Название роли
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users Пользователи, которым назначена эта роль
 */

class Role extends BaseModel
{
    use HasFactory;

    /**
     * Поля, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Пользователи, связанные с этой ролью (многие ко многим).
     *
     * @return BelongsToMany<User>
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
