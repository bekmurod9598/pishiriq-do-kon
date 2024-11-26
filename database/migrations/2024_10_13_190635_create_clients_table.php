<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('branch_id'); // Bog'lanish uchun
            $table->string('client'); // Client nomi
            $table->string('phone'); // Asosiy telefon raqami
            $table->string('phone_extra')->nullable(); // Qo'shimcha telefon raqami
            $table->string('adress'); // Mijoz manzili
            $table->unsignedBigInteger('created_by'); // Kim yaratgan
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps(); // created_at va updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
