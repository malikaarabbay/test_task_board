<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель User.
 *
 * Представляет пользователя системы.
 *
 * @property int $id
 * @property string $name Имя пользователя
 * @property string $email Email
 * @property string $password Хешированный пароль
 * @property string|null $remember_token Токен "запомнить меня"
 * @property \Illuminate\Support\Carbon|null $email_verified_at Дата подтверждения email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles Назначенные роли
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projectsOwned Созданные проекты
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasksCreated Созданные задачи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $assignedTasks Назначенные задачи
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments Комментарии пользователя
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskHistory> $taskHistories История действий пользователя
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Атрибуты, скрытые при сериализации.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Преобразования типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Получить роли пользователя (многие ко многим).
     *
     * @return BelongsToMany<Role>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Проверка наличия конкретной роли у пользователя.
     *
     * @param string $roleName Название роли
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Проекты, созданные пользователем.
     *
     * @return HasMany<Project>
     */
    public function projectsOwned(): HasMany
    {
        return $this->hasMany(Project::class, 'creator_id');
    }

    /**
     * Задачи, созданные пользователем.
     *
     * @return HasMany<Task>
     */
    public function tasksCreated(): HasMany
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    /**
     * Задачи, назначенные пользователю.
     *
     * @return BelongsToMany<Task>
     */
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Комментарии, оставленные пользователем.
     *
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * История изменений задач, связанных с пользователем.
     *
     * @return HasMany<TaskHistory>
     */
    public function taskHistories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    /**
     * Формат сериализации даты (например, для JSON).
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->setTimezone('Asia/Almaty')
            ->format('Y-m-d H:i:s');
    }
}
