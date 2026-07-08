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
            Schema::table('refund_items', function (Blueprint $table) {

                $table->foreignId('refund_id')
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('product_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->integer('quantity');

                $table->decimal('price',10,2);

                $table->decimal('total',10,2);

            });
        }

        public function down()
        {
            Schema::table('refund_items', function (Blueprint $table) {

                $table->dropForeign(['refund_id']);
                $table->dropForeign(['product_id']);

                $table->dropColumn([
                    'refund_id',
                    'product_id',
                    'quantity',
                    'price',
                    'total'
                ]);

            });
        }
};
