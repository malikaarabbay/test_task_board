<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

/**
 * Сервис ProjectService.
 *
 * Отвечает за бизнес-логику, связанную с управлением проектами.
 */
class ProjectService
{
    /**
     * Создание нового проекта.
     *
     * Устанавливает текущего пользователя как автора проекта.
     *
     * @param array $data Данные для создания проекта
     * @return Project Созданный проект
     */
    public function create(array $data): Project
    {
        $data['creator_id'] = Auth::id();

        return Project::create($data);
    }
}
