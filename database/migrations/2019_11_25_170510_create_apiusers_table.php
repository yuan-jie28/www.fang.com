<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiusers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',50)->comment('账号');
            $table->string('password',255)->comment('密码');
            $table->string('token',50)->comment('token 不进行网络传输所用');
            // 规定一天只能请求次数
            $table->unsignedInteger('click')->default(0)->comment('请求次数');
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
        Schema::dropIfExists('apiusers');
    }
}
