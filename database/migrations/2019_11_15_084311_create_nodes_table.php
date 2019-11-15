<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * 权限(节点)表
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',50)->comment('节点名称');
            $table->string('route_name',100)->nullable()->default('')->comment('路由别名，权限认证标识');
            $table->unsignedInteger('pid')->default(0)->comment('上级ID');
            $table->enum('is_menu',['0','1'])->default('0')->comment('是否为菜单0否，1是');
            $table->timestamps();
            $table->softDeletes();
        });

        // 角色与权限中间表
        Schema::create('role_node', function (Blueprint $table) {
            // 角色ID
            $table->unsignedInteger('role_id')->default(0)->comment('角色ID');
            // 节点ID
            $table->unsignedInteger('node_id')->default(0)->comment('节点ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
        Schema::dropIfExists('role_node');

    }
}
