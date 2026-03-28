<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMediaEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'           => 'required|string|max:255',
            'original_title'  => 'nullable|string|max:255',
            'type'            => 'required|string|in:anime,manga,manhwa,manhua,novel', // Using string for validation though it casts to enum
            'status'          => 'required|string|in:watching,rewatching,reading,completed,on_hold,dropped,plan_to_watch',
            'cover_url'       => 'nullable|url|max:2048',
            'mal_id'          => 'nullable|integer|min:1',
            'current_episode' => 'nullable|integer|min:0',
            'total_episodes'  => 'nullable|integer|min:0',
            'current_chapter' => 'nullable|integer|min:0',
            'total_chapters'  => 'nullable|integer|min:0',
            'current_volume'  => 'nullable|integer|min:0',
            'total_volumes'   => 'nullable|integer|min:0',
            'rating'          => 'nullable|integer|min:1|max:5',
            'notes'           => 'nullable|string|max:2000',
        ];
    }
}
