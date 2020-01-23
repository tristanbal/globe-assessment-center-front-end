<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateListOfCompetenciesPerRolesTable extends Migration
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
        Schema::create('list_of_competencies_per_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roleID');
            $table->integer('groupID');
            $table->integer('competencyID');
            $table->integer('competencyTypeID');
            $table->integer('targetLevelID');
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
        Schema::dropIfExists('list_of_competencies_per_roles');
    }
}
