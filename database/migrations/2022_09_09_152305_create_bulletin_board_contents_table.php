<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulletinBoardContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulletin_board_contents', function (Blueprint $table) {
            $table->integer('bulletin_board_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->dateTime('datetime');
            $table->text('content')->nullable();
            $table->primary(['bulletin_board_id', 'datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bulletin_board_contents');
    }
}
