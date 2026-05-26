<?php

namespace App\Http\Requests;

use App\Rules\ValidStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $article = $this->route("article");
        return $this->user() && $this->user()->can("update", $article);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => Str::title(preg_replace('/\s+/', ' ', trim($this->title)))
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $article = $this->route('article');
        $articleId = $article ? $article->id : null;
        return [
            'title'   => ['sometimes', 'required', 'string', 'min:10', "unique:articles,title,{$articleId}"], // يتجاهل المقال الحالي لمنع تضارب الـ unique
            'content' => ['sometimes', 'required', 'string', 'min:100'],
            'tags'    => ['nullable', 'array'],
            'tags.*'  => ['exists:tags,id'],
            'status'  => ['sometimes', 'required', new ValidStatus()],
        ];
    }
}
