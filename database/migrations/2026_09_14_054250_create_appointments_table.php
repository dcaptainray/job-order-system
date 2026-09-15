<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('contract_service_no');
            $table->string('source_of_funds');
            $table->foreignId('pds_id')->constrained('pds')->onDelete('cascade');
            $table->string('designation');
            $table->decimal('rate_per_day', 10, 2);
            $table->date('period_from');
            $table->date('period_to');
            $table->string('office_assignment');
            $table->string('renewal_status');
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};