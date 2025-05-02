<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->string('wool_type')->nullable()->after('weight_kg');
            $table->string('status')->nullable()->after('wool_type');
            $table->date('arrival_date')->nullable()->after('status');
            $table->text('notes')->nullable()->after('arrival_date');
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['wool_type', 'status', 'arrival_date', 'notes']);
        });
    }
};