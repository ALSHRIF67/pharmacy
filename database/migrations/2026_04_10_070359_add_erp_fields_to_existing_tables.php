<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->enum('sync_status', ['pending', 'synched'])->default('pending')->after('base_price');
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->enum('sync_status', ['pending', 'synched'])->default('pending')->after('selling_price');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->enum('sync_status', ['pending', 'synched'])->default('pending')->after('reference_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->enum('sync_status', ['pending', 'synched'])->default('pending')->after('total_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'sync_status']);
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('sync_status');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('sync_status');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'sync_status']);
        });
    }
};
