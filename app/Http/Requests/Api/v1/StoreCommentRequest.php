<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('comment', $this->route('task'));
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'],
        ];
    }
}
