<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $writer = User::where('role', 'writer')->first();
        $reader = User::where('role', 'reader')->first();
        $category = Category::first();
        $tags = Tag::take(3)->get();

        if (!$writer || !$category) {
            return;
        }

        $article = $writer->articles()->create([
            'category_id' => $category->id,
            'title' => 'TechNova Launches Advanced Backend Architecture for 2026',
            'content' => 'The company has officially transitioned to using Redis queues and Laravel Horizon. This setup ensures ultra-fast data processing and builds a highly secure, scalable internal ecosystem.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $article->tags()->attach($tags);

        Attachment::create([
            'user_id'         => $writer->id,
            'file_path'       => 'uploads/articles/architecture_2026.pdf',
            'file_name'       => 'approved_architecture_blueprint.pdf',
            'mime_type'       => 'application/pdf',
            'attachable_id'   => $article->id,
            'attachable_type' => Article::class,
        ]);


        $comment = new Comment([
            'content' => 'Excellent strategic move! This backend update will scale our internal platform efficiency to a whole new level. Kudos to the development team.',
        ]);
        $comment->user()->associate($reader);
        $article->comments()->save($comment);

        Attachment::create([
            'user_id'         => $reader->id,
            'file_path'       => 'uploads/avatars/reader_personal_avatar.png',
            'file_name'       => 'samer_developer_avatar.png',
            'mime_type'       => 'image/png',
            'attachable_id'   => $reader->id,
            'attachable_type' => User::class,
        ]);
    }
}
