<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('order_service_id')
                ->nullable()
                ->unique()
                ->after('user_id')
                ->constrained('order_services')
                ->nullOnDelete();
        });

        Schema::table('order_services', function (Blueprint $table) {
            $table->timestamp('invoiced_at')->nullable()->after('closed_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['order_service_id']);
            $table->dropUnique(['order_service_id']);
            $table->dropColumn('order_service_id');
        });

        Schema::table('order_services', function (Blueprint $table) {
            $table->dropColumn('invoiced_at');
        });
    }
};
