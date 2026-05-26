<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = max(1, ceil($wordCount / 200));

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author_name' => $this->user?->profile
                ? "{$this->user->profile->first_name} {$this->user->profile->last_name}"
                : $this->user->name,
            'published_at' => $this->created_at->toIso8601String(),

            'reading_time' => "{$readingTime} min",
            'comments_count' => (int) $this->comments_count,
            'tags' => $this->tags ? $this->tags->pluck('name')->toArray() : [],
        ];
    }
}
