<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateCompetencyUploadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function up()
    {
        Schema::create('competency_uploaders', function (Blueprint $table) {
            // $table->charset = 'utf8';	
            // $table->collation = 'utf8_unicode_ci';	
            $table->string('clusters');
            $table->string('subclusters');
            $table->string('talentsegments');
            $table->string('competencyNames');
            $table->string('competencyElements');
            $table->text('definitions',10000);
            $table->text('levelOne',10000);
            $table->text('levelTwo',10000);
            $table->text('levelThree',10000);
            $table->text('levelFour',10000);
            $table->text('levelFive',10000);
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
        Schema::dropIfExists('competency_uploaders');
    }
}
