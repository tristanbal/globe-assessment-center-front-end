<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\right;

class CreateRightsTable extends Migration
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
        Schema::create('rights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        //create user right
        $rightuser = new right;
        $rightuser->name = "user";
        $rightuser->save();


        //create admin right
        $rightadmin = new right;
        $rightadmin->name = "admin";
        $rightadmin->save();


        //create superadmin right
        $rightsuperadmin = new right;
        $rightsuperadmin->name = "superadmin";
        $rightsuperadmin->save();
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rights');
    }
}
