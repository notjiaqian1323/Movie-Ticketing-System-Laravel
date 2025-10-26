<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // Hard block client tampering with server-only fields
            'account_id'      => ['prohibited'],
            'edited'          => ['prohibited'],
            'review_datetime' => ['prohibited'],

            'movie_id' => [
                'bail',
                'required',
                'exists:movies,id',
                Rule::unique('reviews', 'movie_id')
                    ->where(fn ($q) => $q->where('account_id', Auth::id())),
            ],

            'rating'       => ['bail','required','integer','between:1,5'],
            'comment'      => ['nullable','string','max:2000'],
            'is_anonymous' => ['sometimes','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'Please choose a movie to review.',
            'movie_id.exists'   => 'The selected movie does not exist.',
            'movie_id.unique'   => 'You already reviewed this movie. Edit your review instead.',
            'rating.required'   => 'Please select a rating from 1 to 5 stars.',
            'rating.between'    => 'Rating must be between 1 and 5.',
            'comment.max'       => 'Your review cannot exceed 2000 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Inject route-bound movie
        if ($movie = $this->route('movie')) {
            $this->merge(['movie_id' => $movie->id]);
        }

        // Normalize
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
