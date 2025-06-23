<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Используем политику для контроля доступа
        return $this->user()->can('create', \App\Models\Task::class);
    }

    protected function prepareForValidation(): void
    {
        // Установим статус по умолчанию, если не передан
        if ($this->missing('status')) {
            $this->merge(['status' => 'open']);
        }
    }

    public function rules(): array
    {
        return [
            'project_id'   => ['required', 'exists:projects,id'],
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'status'       => ['nullable', Rule::in(['open', 'in_progress', 'completed', 'blocked'])],
            'priority'     => ['required', Rule::in(['low', 'medium', 'high'])],
            'deadline'     => ['nullable', 'date', 'after_or_equal:today'],
            'executors'    => ['nullable', 'array'],
            'executors.*'  => ['exists:users,id'],
        ];
    }
}
