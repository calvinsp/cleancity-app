<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lokasi_tps', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat');
            $table->integer('kapasitas');
            $table->string('jam_operasional');
            $table->timestamps();
        });

        \DB::table('lokasi_tps')->insert([
            ['nama' => 'TPS Pusat', 'alamat' => 'Jl. Sudirman No. 1', 'kapasitas' => 100, 'jam_operasional' => '06:00 - 18:00', 'created_at' => now()],
            ['nama' => 'TPS Timur', 'alamat' => 'Jl. Gatot Subroto No. 5', 'kapasitas' => 80, 'jam_operasional' => '06:00 - 18:00', 'created_at' => now()],
            ['nama' => 'TPS Barat', 'alamat' => 'Jl. Ahmad Yani No. 10', 'kapasitas' => 90, 'jam_operasional' => '06:00 - 18:00', 'created_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('lokasi_tps');
    }
};
