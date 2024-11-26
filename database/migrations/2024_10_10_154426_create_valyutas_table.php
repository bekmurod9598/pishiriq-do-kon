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
        Schema::create('valyutas', function (Blueprint $table) {
            $table->id(); // Auto incrementing primary key
            $table->string('valyuta'); // Valyuta nomi yoki kodi
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable(); // 'deleted_at' ustuni(soft delete uchun)
            $table->timestamps(); // 'created_at' va 'updated_at' ustunlari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valyutas');
    }
};
