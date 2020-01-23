<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employeeID');
            $table->string('email');
            $table->string('password');
            $table->integer('rightID');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        //create admin
        $admin = new user;
        $admin->employeeID = "admin@email.com";
        $admin->email = "admin@email.com";
        $admin->password = '$2y$10$A/yJ/yc1xs.Wnlom0AUirOAc7Go27tPJQ9q9Ug0Kbxinub076oVfS';
        $admin->rightID = 2;
        $admin->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
