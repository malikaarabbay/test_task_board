<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'task_id'    => $this->task_id,
            'action'     => $this->action,
            'comment'    => $this->comment,
            'created_at' => $this->created_at,
            'user'       => new UserResource($this->user),
        ];
    }
}
