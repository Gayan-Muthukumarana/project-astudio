<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * @return string[]
     */
    public static function getAllowedFilters()
    {
        return ['name', 'type'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
