<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $movie_id
 * @property int|null $account_id
 * @property string $rating
 * @property string|null $description
 * @property string $review_datetime
 * @property int $edited
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Movie $movie
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereEdited($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereReviewDatetime($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'account_id',
        'rating',
        'comment',
        'review_datetime',
        'is_anonymous',
        'edited',
    ];

    protected $casts = [
        'is_anonymous'    => 'boolean',
        'edited'          => 'boolean',
        'review_datetime' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}