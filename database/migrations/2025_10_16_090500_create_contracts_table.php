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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->text('notes')->nullable();

            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->enum('status', ['active','pending','expired','cancelled'])->default('pending');

            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
