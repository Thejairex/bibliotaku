<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParseMediaImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimetypes:application/json,text/plain,text/json'],
        ];
    }
}
