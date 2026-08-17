<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (! Schema::hasTable('rooms')) {
            return;
        }

        DB::table('rooms')->where('room_type', 'single')->update(['room_type' => '1in1']);

        DB::table('rooms')
            ->where('room_type', 'shared')
            ->where('bedrooms', '>=', 4)
            ->update(['room_type' => '4in1']);

        DB::table('rooms')
            ->where('room_type', 'shared')
            ->where('bedrooms', 3)
            ->update(['room_type' => '3in1']);

        DB::table('rooms')
            ->where('room_type', 'shared')
            ->update(['room_type' => '2in1']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE rooms ALTER COLUMN room_type SET DEFAULT '1in1'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('rooms')) {
            return;
        }

        DB::table('rooms')->where('room_type', '1in1')->update(['room_type' => 'single']);
        DB::table('rooms')->whereIn('room_type', ['2in1', '3in1', '4in1'])->update(['room_type' => 'shared']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE rooms ALTER COLUMN room_type SET DEFAULT 'single'");
        }
    }
};
