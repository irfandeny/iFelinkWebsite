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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama paket
            $table->enum('provider', ['Telkomsel', 'Indosat', 'XL', 'Tri', 'Smartfren', 'Axis']); // Provider
            $table->string('quota'); // Kuota
            $table->decimal('price', 10, 2); // Harga paket
            $table->integer('validity_days'); // Masa aktif (hari)
            $table->text('description')->nullable(); // Deskripsi paket
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->timestamps();
            $table->softDeletes(); // Soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
