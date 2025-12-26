<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jenis_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        \DB::table('jenis_sampah')->insert([
            ['nama' => 'Sampah Organik', 'deskripsi' => 'Sampah dari makanan, daun, dll', 'created_at' => now()],
            ['nama' => 'Sampah Anorganik', 'deskripsi' => 'Plastik, kertas, logam', 'created_at' => now()],
            ['nama' => 'Sampah B3', 'deskripsi' => 'Bahan berbahaya dan beracun', 'created_at' => now()],
            ['nama' => 'Sampah Elektronik', 'deskripsi' => 'Limbah elektronik', 'created_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('jenis_sampah');
    }
};
