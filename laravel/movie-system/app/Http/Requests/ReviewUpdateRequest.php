<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReviewUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $review = $this->route('review'); 
        return Auth::check() && $review && (int)$review->account_id === (int)Auth::id();
    }

    public function rules(): array
    {
        return [
            // Block client tampering with server-only or immutable fields
            'movie_id'        => ['prohibited'],
            'account_id'      => ['prohibited'],
            'edited'          => ['prohibited'],
            'review_datetime' => ['prohibited'],

            'rating'       => ['bail','required','integer','between:1,5'],
            'comment'      => ['nullable','string','max:2000'],
            'is_anonymous' => ['sometimes','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_anonymous')) {
            $this->merge(['is_anonymous' => (bool) $this->boolean('is_anonymous')]);
        }
        if ($this->has('rating')) {
            $this->merge(['rating' => (int) $this->input('rating')]);
        }
        if ($this->filled('comment')) {
            $this->merge(['comment' => trim((string)$this->input('comment'))]);
        }
    }
}
