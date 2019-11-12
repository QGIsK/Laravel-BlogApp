<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'post' => $this->post,
        ];
    }
}
