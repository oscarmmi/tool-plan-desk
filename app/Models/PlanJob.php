<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanJob extends Model
{
    protected $fillable = [
        'question',
        'status',
    ];

    /** @return HasOne<ToolPlan, $this> */
    public function toolPlan(): HasOne
    {
        return $this->hasOne(ToolPlan::class);
    }
}
