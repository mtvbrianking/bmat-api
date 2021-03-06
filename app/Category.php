<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    protected $table = 'categories';

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

    // Relationships

    /**
     * Posts in this category.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_name', 'name');
    }

    /**
     * Parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_name', 'name');
    }

    /**
     * Sub categories;.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_name', 'name');
    }

    // Mutators

    /**
     * Set category name; slug-case.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = str_slug(str_singular($value));
    }

    /**
     * Set parent category name; slug-case.
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setParentNameAttribute($value)
    {
        $this->attributes['parent_name'] = str_slug(str_singular($value));
    }

    // Accessors

    /**
     * Get category name; Title Case.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return str_replace('-', ' ', title_case($value));
    }

    /**
     * Get parent category name; Title Case.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getParentNameAttribute($value)
    {
        return str_replace('-', ' ', title_case($value));
    }
}
