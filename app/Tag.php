<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
    protected $table = 'tags';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

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
        'name',
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

    // relationships

    /**
     * Tagged posts.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tag_name');
    }

    // mutators

    /**
     * Set category name; slug-case.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = str_slug($value);
    }

    // accessors

    /**
     * Get tag name; StudlyCase.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return studly_case($value);
    }

    // scopes
}
