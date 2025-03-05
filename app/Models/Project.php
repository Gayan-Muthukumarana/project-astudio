<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Filterable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status'
    ];

    /**
     * @return string[]
     */
    public static function getAllowedFilters()
    {
        return ['name', 'status'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(AttributeValue::class, 'entity_id');
    }
}
