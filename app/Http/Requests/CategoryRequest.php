<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ignora a própria categoria ao checar o parent_id (evita virar mãe dela mesma)
        $categoryId = $this->route('category')?->id;

        return [
            'name'      => 'required|string|max:100',
            'color'     => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon'      => 'required|string|max:60',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                'different:' . $categoryId,
            ],
        ];
    }

    /**
     * Mensagens customizadas de validação.
     */
    public function messages(): array
    {
        return [
            'color.regex' => 'A cor precisa estar em formato hexadecimal (ex: #0c8fe6).',
            'parent_id.different' => 'Uma categoria não pode ser subcategoria dela mesma.',
        ];
    }
}