<?php
// Name: CHONG KA HONG
// Student ID: 2314524
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\UserTypes\UserFactory;
use App\UserTypes\UserType;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $gender
 * @property string $role
 * @property string|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Database\Factories\AccountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUsername($value)
 * @mixin \Eloquent
 */
class Account extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'accounts';

    protected $fillable = [
        'phone',
        'date_of_birth',
        'username',
        'email',
        'password',
        'gender',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        // Add this line to automatically hash the password
        'password' => 'hashed',
    ];

    /**
     * Get the UserType instance for the current authenticated account.
     * This allows chaining methods like Auth::user()->asUserType()->getHomeData();
     */
    public function asUserType(): UserType
    {
        $userFactory = app(\App\UserTypes\UserFactory::class);
        return $userFactory->create($this->role);
    }

    //  means Laravel will not automatically manage the created_at and 
//  updated_at timestamp colums for this model's database table because database dont have 
//  these column
    public $timestamps = false;

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}
