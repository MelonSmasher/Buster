<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('scheme_id')->nullable();
            $table->unsignedInteger('server_id')->nullable();
            $table->unsignedInteger('proxy_id')->nullable();
            $table->string('url');
            $table->string('path');
            $table->integer('response_code');
            $table->string('reason');
            $table->json('action');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('scheme_id')->references('id')->on('schemes')->onDelete('cascade');
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->foreign('proxy_id')->references('id')->on('proxies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purges', function (Blueprint $table) {
            $table->dropForeign('purges_scheme_id_foreign');
            $table->dropForeign('purges_server_id_foreign');
            $table->dropForeign('purges_proxy_id_foreign');
        });

        Schema::dropIfExists('purges');
    }
}
