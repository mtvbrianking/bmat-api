<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    #region: relationships

    /**
     * Commenter.
     */
    public function commenter()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Post responded to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    /**
     * Comment responded to.
     */
    public function subject()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    /**
     * Comment responses.
     */
    public function responses()
    {
        return $this->hasMany(Comment::class, 'comment_id', 'id');
    }

    /**
     * Comment likes.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable', 'likeable_type');
    }

    #endregion

    #region: mutators

    #endregion

    #region: accessors

    #endregion

    #region: scopes

    #endregion
}
