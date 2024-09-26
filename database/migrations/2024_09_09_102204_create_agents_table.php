<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable(false);
            $table->string('email', 100)->nullable(false);
            $table->string('phone_no', 15)->nullable(false);
            $table->string('alternate_phone_no', 15);
            $table->string('contact_person', 100);
            $table->foreignId('sub_location_id')->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
