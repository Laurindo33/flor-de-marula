<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('benefits')->nullable()->after('description');
            $table->text('ingredients_list')->nullable()->after('benefits');
            $table->text('how_to_use')->nullable()->after('ingredients_list');
            $table->text('expert_review')->nullable()->after('how_to_use');
            $table->text('shipping_returns')->nullable()->after('expert_review');
            $table->foreignId('routine_product_id')->nullable()->after('is_best_seller')
                ->constrained('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('routine_product_id');
            $table->dropColumn(['benefits', 'ingredients_list', 'how_to_use', 'expert_review', 'shipping_returns']);
        });
    }
};
