<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the phone number in general_settings table
        DB::table('general_settings')
            ->where('id', 1)
            ->update(['contact_number' => '01473 807117']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the phone number back to the old number
        DB::table('general_settings')
            ->where('id', 1)
            ->update(['contact_number' => '07398 951462']);
    }
};
