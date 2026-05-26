<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author_name' => $this->user?->profile
                ? "{$this->user->profile->first_name} {$this->user->profile->last_name}"
                : $this->user->name,
            'published_at' => $this->created_at->toIso8601String(),
        ];
    }
}
