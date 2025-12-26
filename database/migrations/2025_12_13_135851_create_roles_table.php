<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->unique();
            $table->timestamps();
        });

        \DB::table('roles')->insert([
            ['role_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'petugas', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('roles');
    }
};
