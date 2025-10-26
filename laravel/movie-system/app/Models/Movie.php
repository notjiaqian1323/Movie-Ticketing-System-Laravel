<?php
namespace App\Models;

//Name: HO YI VON
//Student ID : 23WMR14542

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\States\MovieState;
use App\States\ComingSoonState;
use App\States\NowShowingState;
use App\States\ArchivedState;
use App\States\ReReleasedState;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $title
 * @property string $genre
 * @property string $director
 * @property string $cast
 * @property string|null $synopsis
 * @property int|null $duration
 * @property string|null $language
 * @property string|null $subtitles
 * @property string|null $age_rating
 * @property string|null $status
 * @property \Illuminate\Support\Carbon $release_date
 * @property string|null $image_path
 * @property int $is_popular
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereAgeRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereAvgReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereCast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereSubtitles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereSynopsis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Movie whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'genre', 
        'director', 
        'cast', 
        'synopsis', 
        'duration', 
        'language', 
        'subtitles', 
        'age_rating', 
        'status', 
        'release_date', 
        'image_path', 
        'is_popular'
    ];

    // convert string date to carbon object
    protected $casts = [
        'release_date' => 'date',
    ];

    // stateInstance to hold the state class / null value, 
    // avoid repeating create new instance of same state class
    protected ?MovieState $stateInstance = null;
    
    /**
     * Get the schedules for the movie.
     * Used for retrieving all showtimes for a movie.
     */
    public function schedules() 
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the reviews for the movie.
     * Used for displaying all reviews associated with a movie.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Convert image path(filename) and to the full URL for the movie's image.
     * Used for displaying the movie poster or movie card
     * $movie->image_url
     */
    protected function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/movies/' . $this->image_path);
        }
        return asset('storage/icons/default.jpg');
    }

    /**
     * Scope a query to find related movies based on genre.
     * Used for suggesting other movies with the same genre.
     */
    Public function scopeRelated(Builder $query, Movie $movie): Builder
    {
        return $query->where('genre', $movie->genre)
                    ->where('id', '!=', $movie->id) 
                    ->inRandomOrder()
                    ->take(4);
    }

    // define which class to use based on current state
    protected $states = [
        'coming_soon' => ComingSoonState::class,
        'now_showing' => NowShowingState::class,
        'archived' => ArchivedState::class,
        're_released' => ReReleasedState::class,
    ];

    // define which class to use based on current state
    public function getState(): MovieState
    {
        // but each time change state need to set t to null
        if($this->stateInstance === null){
            // app(ClassName::class) - fetch an insance of the specific state class
            $this->stateInstance = app($this->states[$this->status] ?? ComingSoonState::class);
        }
        
        return $this->stateInstance;
    }

    /**
     * Display the human-readable status of the movie.
     * Used for showing the movie's current status on the front-end.
     */
    public function displayStatus(): string
    {
        // pass the movie instance to the specific state class
        // NowShowingState->displayStatus($this)
        return $this->getState()->displayStatus($this);
    }

    /**
     * Update the movie's status and save it.
     * Used for changing the state of the movie, e.g., from 'coming_soon' to 'now_showing'.
     */
    public function updateStatus(string $newStatus): void
    {
        $this->status = $newStatus;
        $this->save();
        $this->stateInstance = null;
    }

    /**
     * Check if booking is allowed for the movie's current status.
     * Used for enabling or disabling the booking button.
     */    
    public function isBookingAllowed(): bool
    {
        return $this->getState()->isBookingAllowed($this);
    }

    /**
     * Add the movie to the popular list.
     * Used for marking a movie as popular, which may affect its visibility.
     */
    public function addToPopularList(): string
    {
        return $this->getState()->addToPopularList($this);
    }

    /**
     * Remove the movie from the popular list.
     * Used for un-marking a movie as popular.
     */
    public function removeFromPopularList(): string{
        return $this->getState()->removeFromPopularList($this);
    }

    /**
     * Check if writing a review is allowed for the movie's current status.
     * Used for enabling or disabling the review submission form.
     */    public function writeReview(): bool
    {
        return $this->getState()->writeReview($this);
    }

    /**
     * Remove the movie from active listings.
     * Used for archiving or deactivating a movie from display.
     */
    public function removeFromActiveListing(): string
    {
        return $this->getState()->removeFromActiveListing($this);
    }

    /**
     * Add the movie back to active listings.
     * Used for making a movie visible again, e.g., for 're_released' status.
     */
    public function addToActiveListing(): string
    {
        return $this->getState()->addToActiveListing($this);
    }

}
