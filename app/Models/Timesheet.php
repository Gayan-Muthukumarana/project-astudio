<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use Filterable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'task_name',
        'date',
        'hours'
    ];

    /**
     * @return string[]
     */
    public static function getAllowedFilters()
    {
        return ['task_name', 'date', 'hours', 'user_id', 'project_id'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
