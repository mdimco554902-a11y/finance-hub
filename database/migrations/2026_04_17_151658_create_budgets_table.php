<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('budgets', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->decimal('limit_amount', 10, 2);
        $table->string('color')->default('#2563EB'); // For the progress bar color
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
