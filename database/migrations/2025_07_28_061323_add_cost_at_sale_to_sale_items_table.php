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
            Schema::table('sale_items', function (Blueprint $table) {
                $table->decimal('cost_at_sale', 10, 2)->nullable();
            });
        }

        public function down(): void
        {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropColumn('cost_at_sale');
            });
        }

};
