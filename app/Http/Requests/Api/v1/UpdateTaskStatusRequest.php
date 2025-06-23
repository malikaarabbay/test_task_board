<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Task;
use App\Rules\CompletedDependencies;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['open', 'in_progress', 'completed', 'blocked']),
                new CompletedDependencies($this->route('task')),
            ],
            'comment' => ['required_if:status,blocked', 'string', 'max:500'],
        ];
    }
}
