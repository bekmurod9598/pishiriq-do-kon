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
       Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->string('cost');
            $table->unsignedBigInteger('cost_type_id'); // cost_types jadvaliga ishora qiluvchi ustun
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        
            // Foreign key bilan bog'lash
            $table->foreign('cost_type_id')->references('id')->on('cost_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
