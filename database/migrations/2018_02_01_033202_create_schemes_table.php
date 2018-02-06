<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->unsignedInteger('server_uri_id')->nullable();
            $table->string('proxy_ids')->nullable();
            $table->unsignedInteger('proxy_uri_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->foreign('server_uri_id')->references('id')->on('uris')->onDelete('cascade');
            $table->foreign('proxy_uri_id')->references('id')->on('uris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->dropForeign('schemes_server_id_foreign');
            $table->dropForeign('schemes_server_uri_id_foreign');
            $table->dropForeign('schemes_proxy_uri_id_foreign');
        });

        Schema::dropIfExists('schemes');
    }
}
