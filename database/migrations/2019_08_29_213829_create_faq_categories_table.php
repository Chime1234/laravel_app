<?php
/*
 * File name: 2019_08_29_213829_create_faq_categories_table.php
 * Last modified: 2021.04.20 at 11:19:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateFaqCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('faq_categories');
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('faq_categories');
    }
}
