<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateEmployeeDatasTable extends Migration
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
        Schema::create('employee_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employeeID');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');
            $table->string('nameSuffix');
            $table->integer('roleID');
            $table->integer('jobID');
            $table->integer('bandID');
            $table->integer('groupID');
            $table->integer('divisionID');
            $table->integer('departmentID');
            $table->integer('sectionID');
            $table->string('supervisorID');
            $table->string('email');
            $table->string('phone');
            $table->boolean('isActive');
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
        Schema::dropIfExists('employee_datas');
    }
}
