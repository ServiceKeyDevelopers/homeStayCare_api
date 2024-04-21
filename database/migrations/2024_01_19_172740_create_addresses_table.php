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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained('applications')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained('countries')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('post_code')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('passport_type')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport')->nullable();
            $table->text('address')->nullable();
            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->string('kin_first_name')->nullable();
            $table->string('kin_middle_name')->nullable();
            $table->string('kin_last_name')->nullable();
            $table->string('kin_phone_number')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
