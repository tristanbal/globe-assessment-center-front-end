<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateCompetencyPerRoleUploadersTable extends Migration
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
        Schema::create('competency_per_role_uploaders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('groupNames', 1000);
            $table->string('roleNames', 1000);
            $table->string('competencyNames', 1000);
            $table->string('priorities', 100);
            $table->integer('targetWeights');
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
        Schema::dropIfExists('competency_per_role_uploaders');
    }
}
