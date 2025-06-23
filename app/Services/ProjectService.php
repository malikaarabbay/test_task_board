<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    /**
     * Создание нового проекта.
     *
     * @param  array  $data
     * @return Project
     */
    public function create(array $data): Project
    {
        $data['creator_id'] = Auth::id();

        return Project::create($data);
    }
}
