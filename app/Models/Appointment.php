<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'contract_service_no',
        'source_of_funds',
        'pds_id',
        'designation',
        'rate_per_day',
        'period_from',
        'period_to',
        'office_assignment',
        'renewal_status',
        'status',
        'document_path',
    ];

    protected $attributes = [
        'status' => 'Pending',
    ];

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }
}