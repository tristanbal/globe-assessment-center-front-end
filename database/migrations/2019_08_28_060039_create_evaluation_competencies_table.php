<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_competencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('evaluationID');
            $table->integer('competencyID');
            $table->integer('givenLevelID');
            $table->integer('targetLevelID');
            $table->double('weightedScore');
            $table->integer('competencyTypeID');
            $table->string('verbatim', 1000);
            $table->string('additional_file');
            $table->softDeletes();
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
        Schema::dropIfExists('evaluation_competencies');
    }
}
