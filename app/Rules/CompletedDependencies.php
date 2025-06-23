<?php

namespace App\Rules;

use App\Models\Task;
use Illuminate\Contracts\Validation\Rule;

class CompletedDependencies implements Rule
{
    protected Task $task;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value !== 'completed') {
            return true;
        }

        // Проверка на наличие незавершённых зависимостей
        return !$this->task
            ->dependencies()
            ->where('status', '<>', 'completed')
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The task cannot be marked as completed until all its dependencies are completed.';
    }
}
