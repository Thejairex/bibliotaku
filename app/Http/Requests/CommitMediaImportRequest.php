<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommitMediaImportRequest extends FormRequest
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
            'fallback_type' => 'required|string|in:anime,manga,manhwa,manhua,novel',
            'mapping' => 'array',
            'mapping.*' => 'string|in:anime,manga,manhwa,manhua,novel',

            'entries' => 'required|array|min:1|max:5000',
            'entries.*.title' => 'required|string|max:255',
            'entries.*.original_title' => 'nullable|string|max:255',
            'entries.*.cover_url' => 'nullable|url|max:2048',
            'entries.*.notes' => 'nullable|string|max:2000',
            'entries.*.current_chapter' => 'nullable|integer|min:0',
            'entries.*.total_chapters' => 'nullable|integer|min:0',
            'entries.*.inferred_status' => 'required|string|in:watching,rewatching,reading,completed,on_hold,dropped,plan_to_watch',
            'entries.*.category_ids' => 'array',
            'entries.*.category_ids.*' => 'string',
        ];
    }
}
