<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property Attribute $name
 *
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $appends = [
        'name',
    ];

    /**
    * @return HasMany
    */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
    * @return HasMany
    */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
    * @return Attribute
    */
    protected function name(): Attribute
    {
        return new Attribute(fn () => $this->first_name . ' ' . $this->last_name);
    }
}
