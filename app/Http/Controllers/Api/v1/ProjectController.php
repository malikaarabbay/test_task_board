<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;

class ProjectController extends ApiController
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * POST /api/projects — создание нового проекта.
     * Только для пользователей с ролями admin или manager.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->create($request->validated());

        return $this->responseWithData(new ProjectResource($project));
    }
}
