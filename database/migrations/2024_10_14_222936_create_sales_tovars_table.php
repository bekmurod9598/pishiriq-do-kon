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
         Schema::create('sales_tovars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('madel_id');
            $table->unsignedBigInteger('soni');
            $table->decimal('sotuv_narx', 8, 2); // float o'rniga decimal ishlatildi
            $table->decimal('chegirma', 8, 2); // float o'rniga decimal ishlatildi
            $table->decimal('chiqim_narx', 8, 2); // float o'rniga decimal ishlatildi
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_tovars');
    }
};
