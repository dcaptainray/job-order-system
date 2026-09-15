<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pds extends Model
{
    protected $table = 'pds';

    protected $fillable = [
        'unique_id',
        'last_name',
        'first_name',
        'middle_name',
        'middle_initial', // Include if your form or legacy code still uses this
        'name_extension',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'civil_status',
        'height',
        'weight',
        'blood_type',
        'citizenship',
        'gsis',
        'pagibig',
        'philhealth',
        'sss',
        'tin',
        'agency_employee_no',
        'residential_address',
        'permanent_address',
        'telephone',
        'mobile_no',
        'email_address',
        'spouse_last_name',
        'spouse_first_name',
        'spouse_middle_name',
        'father_last_name',
        'father_first_name',
        'father_middle_name',
        'mother_maiden_last_name',
        'mother_maiden_first_name',
        'mother_maiden_middle_name',
        'school_name',
        'degree_course',
        'school_period',
        'year_graduated',
        'eligibility',
        'eligibility_rating',
        'work_experience_summary',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}