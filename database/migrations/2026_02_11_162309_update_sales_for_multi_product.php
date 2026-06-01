<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropForeign(['product_id']);
        $table->dropColumn(['product_id', 'quantity', 'total_price']);
        $table->decimal('total', 10, 2)->default(0)->after('id');
    });
}
    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->integer('quantity');
        $table->decimal('total_price', 10, 2);
        $table->dropColumn('total');
    });
}
};
