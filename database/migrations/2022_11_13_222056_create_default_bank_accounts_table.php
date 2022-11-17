<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
        Schema::create('default_bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(new Expression('gen_random_uuid()'));
            $table->string('type');
            $table->integer('account_check_digit');
            $table->integer('branch_check_digit');
            $table->double('account_check_number');
            $table->double('branch_number');
            $table->integer('bank');
            $table->string('holder_name');
            $table->foreignUuid('recipient_id')->references('id')->on('recipients')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_bank_accounts');
    }
};
