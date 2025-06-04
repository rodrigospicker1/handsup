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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_admin')->default(0);

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable()->nullable();
            $table->string('cpf', 11)->unique()->index()->nullable();
            $table->string('cpf_formatted', 14)->unique()->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone', 15)->nullable();
            $table->string('cell_phone', 15)->nullable();
            $table->string('whatsapp', 15)->nullable();
            $table->string('date_of_birth', 10)->nullable();
            $table->string('gender', 1)->nullable();
            $table->string('company_code', 100)->nullable()->unique()->index();
            $table->string('username', 100)->nullable()->unique()->index();
            $table->unsignedBigInteger('country_id')->default(33);
            $table->string('zip_code', 10)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('address_number', 20)->nullable();
            $table->string('address_complement', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('ibge', 15)->nullable();
            $table->string('ddd', 4)->nullable();
            $table->string('siafi', 20)->nullable();
            $table->string('name_mother', 100)->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
