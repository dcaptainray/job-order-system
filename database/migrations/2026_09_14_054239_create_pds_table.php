<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pds', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('gsis')->nullable();
            $table->string('pagibig')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('sss')->nullable();
            $table->string('tin')->nullable();
            $table->string('agency_employee_no')->nullable();
            $table->text('residential_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            // Family
            $table->string('spouse_last_name')->nullable();
            $table->string('spouse_first_name')->nullable();
            $table->string('spouse_middle_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('mother_maiden_last_name')->nullable();
            $table->string('mother_maiden_first_name')->nullable();
            $table->string('mother_maiden_middle_name')->nullable();
            // Education & Eligibility
            $table->string('school_name')->nullable();
            $table->string('degree_course')->nullable();
            $table->string('school_period')->nullable();
            $table->string('year_graduated')->nullable();
            $table->string('eligibility')->nullable();
            $table->string('eligibility_rating')->nullable();
            $table->text('work_experience_summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pds');
    }
};