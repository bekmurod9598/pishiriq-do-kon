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
        Schema::create('faktura_inputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consignor_id');
            $table->string('faktura');
            $table->unsignedBigInteger('valyuta_id');
            $table->decimal('valyuta_kurs', 8, 2); // float o'rniga decimal ishlatildi
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            // $table->foreign('consignor_id')->references('id')->on('consignors')->onDelete('cascade');
            // $table->foreign('valyuta_id')->references('id')->on('valyutas')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktura_inputs'); // To'g'ri jadval nomi 'madels'
    }
};
