<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'status'       => $this->status,
            'priority'     => $this->priority,
            'deadline'     => $this->deadline,
            'project'      => new ProjectResource($this->project),
            'creator'      => new UserResource($this->creator),
            'executors'    => UserResource::collection($this->executors),
            'dependencies' => TaskResource::collection($this->dependencies),
            'comments'     => CommentResource::collection($this->comments),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
