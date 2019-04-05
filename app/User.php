<?php

namespace App;

use App\Traits\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Uuids, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['email_verified_at', 'deleted_at'];

    // relationships

    /**
     * User posts.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_id', 'id');
    }

    /**
     * User comments.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_id', 'id');
    }

    /**
     * User posts' | comments' likes.
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }
}
