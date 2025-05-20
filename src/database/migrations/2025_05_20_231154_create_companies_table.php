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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('legal_name');
            $table->string('tax_id')->nullable(); // Matricule fiscal
            $table->string('trade_register')->nullable(); // Registre de commerce
            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Tunisie');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_main')->default(false); // Entreprise principale ou filiale
            $table->foreignId('parent_company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
