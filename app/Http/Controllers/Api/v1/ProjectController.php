<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления проектами.
 *
 * Обрабатывает действия, связанные с созданием проектов.
 */
class ProjectController extends ApiController
{
    /**
     * Сервис для работы с проектами.
     *
     * @var ProjectService
     */
    protected ProjectService $projectService;

    /**
     * Конструктор контроллера.
     *
     * @param ProjectService $projectService Сервис управления проектами
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Создание нового проекта.
     *
     * Доступно только пользователям с ролями `admin` или `manager`.
     * Использует StoreProjectRequest для авторизации и валидации.
     *
     * @param StoreProjectRequest $request Валидированный запрос с полями `name`, `description`
     * @return JsonResponse JSON-ответ с данными нового проекта
     *
     * @route POST /api/projects
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->create($request->validated());

        return $this->responseWithData(new ProjectResource($project));
    }
}
