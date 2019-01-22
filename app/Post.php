<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
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
    protected $table = 'posts';

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
        'title',
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
     * Post author.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Post category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_name', 'name');
    }

    /**
     * Post responses (comments).
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * Post tags.
     */
    public function tags()
    {
        return $this->BelongsToMany(Tag::class, 'post_tag', 'post_id');
    }

    /**
     * Post likes.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable', 'likeable_type');
    }

    #endregion

    #region: mutators

    /**
     * Set post title; slug-case.
     *
     * @param  string $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = str_slug($value);
    }

    #endregion

    #region: accessors

    /**
     * Get post title; Title Case.
     *
     * @param  string $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return str_replace("-", " ", title_case($value));
    }

    #endregion

    #region: scopes

    #endregion
}
