<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view', \App\Models\Task::class);
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|string|in:open,in_progress,blocked,completed',
            'priority' => 'sometimes|string|in:low,medium,high',
            'project_id' => 'sometimes|integer|exists:projects,id',
        ];
    }
}
