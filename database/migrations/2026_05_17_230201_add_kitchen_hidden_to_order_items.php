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
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('kitchen_hidden')->default(false)->after('deleted_by');
            $table->foreignId('kitchen_hidden_by')->nullable()->constrained('users')->after('kitchen_hidden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['kitchen_hidden_by']);
            $table->dropColumn(['kitchen_hidden_by', 'kitchen_hidden']);
        });
    }
};
