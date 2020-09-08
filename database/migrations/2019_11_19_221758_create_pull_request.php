<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePullRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            //$table->boolean('merged');
           // $table->boolean('approved');
            $table->boolean('CompleteazaDateDeBazaFurnizor');
            $table->boolean('CreeazaContFurnizor');
            $table->boolean('VerificareFurnizor');
            $table->boolean('IntraInCont');

            $table->string('state');
            $table->binary('serialized_workflow');
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
        Schema::dropIfExists('pull_requests');
    }
}
