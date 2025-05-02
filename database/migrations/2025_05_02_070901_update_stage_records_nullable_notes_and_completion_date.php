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
        Schema::table('stage_records', function (Blueprint $table) {
            $table->text('notes')->nullable()->change();
            $table->timestamp('completion_date')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stage_records', function (Blueprint $table) {
            $table->text('notes')->nullable(false)->change();
            $table->dropColumn('completion_date');
        });
    }
};
