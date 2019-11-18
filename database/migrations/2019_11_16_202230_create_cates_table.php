<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatesTable extends Migration
{
    /**
     * 文章分类
     */
    public function up()
    {
        Schema::create('cates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cname',50)->comment('分类名称');
            $table->unsignedInteger('pid')->default(0)->comment('上级ID');
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
        Schema::dropIfExists('cates');
    }
}
