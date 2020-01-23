<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateMasterlistsTable extends Migration
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
        Schema::create('masterlists', function (Blueprint $table) {
            // $table->increments('id');
            $table->string('groups');
            $table->string('divisions');
            $table->string('departments');
            $table->string('sections');
            $table->string('employeeID');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');
            $table->string('nameSuffix');
            $table->string('roles');
            $table->string('bands');
            $table->string('supervisorID');
            $table->string('email');
            $table->string('phone');
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
        Schema::dropIfExists('masterlists');
    }
}
