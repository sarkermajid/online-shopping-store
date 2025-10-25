<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('type')->default('0')->comment('1 = percentage, 0 = fixed');
            $table->string('code');
            $table->integer('limit');
            $table->integer('used')->default('0');
            $table->double('discount');
            $table->string('expire_date');
            $table->tinyInteger('status')->default('0')->comment('0 = inactive, 1 = active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
};
