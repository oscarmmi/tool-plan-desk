<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolPlan extends Model
{
    protected $fillable = [
        'plan_job_id',
        'tools',
        'rationale',
    ];

    /** @return array{tools: 'array'} */
    protected function casts(): array
    {
        return [
            'tools' => 'array',
        ];
    }

    /** @return BelongsTo<PlanJob, $this> */
    public function planJob(): BelongsTo
    {
        return $this->belongsTo(PlanJob::class);
    }
}
