<?php

namespace App\Http\Requests\api\posts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|string|max:250",
            "slug" => "required|string|unique:posts,slug|regex:/^[a-z0-9\-]+$/",
            "body" => "required|string",
            "publish_date" => 'required|date|after_or_equal:today',
            "is_published" => "nullable|boolean",
            "meta_description" => "nullable|string",
            "tags" => "nullable|array|max:50",
            "tags.*" => "string|max:50",
            "keywords" => "nullable|array|max:10",
            "keywords.*" => "string|max:50",
        ];
    }

    public function messages(): array
    {
        return [
            "title.max" => "the title must be :max characters at most.",
            "title.required" => "the title is required for making post.",
            "slug.regex" => "the slug must contain only lowercase letters, numbers, and hyphens.",
            "body.required" => "the body is required for making post.",
            "tags.array" => "the tags attribute must be array.",
            "tags.*.max" => "each tag must be :max char at most",
            "tags.*.string" => "each tag must be string type",
            "keywords.array" => "the keywords attribute must be an array.",
            "keywords.*.max" => "each keyword must be :max char at most",
            "keywords.*.string" => "each keyword must be string type",
        ];
    }

    public function attributes(): array
    {
        return [
            "title" => "Title",
            "body" => "Post content",
            "tags" => "Tags",
            "publish_date" => "Publicatin Date",
            "slug" => "URL-slug",
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->has("slug"))
            $this->merge([
                "slug" => Str::slug($this->title),
            ]);
        
        if (!$this->has("publish_date"))
            $this->merge([
                "publish_date" => now(),
            ]);
    }

    protected function failedValidqation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                "status" => false,
                "message" => "data validation failed",
                "errors" => $validator->errors()
            ], 422)
        );
    }
}
