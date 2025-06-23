<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Проверка, есть ли у пользователя указанная роль.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function projectsOwned()
    {
        return $this->hasMany(Project::class, 'creator_id');
    }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function taskHistories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    /**
     * Переопределяем сериализацию даты для JSON.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->setTimezone('Asia/Almaty')
            ->format('Y-m-d H:i:s');
    }
}
