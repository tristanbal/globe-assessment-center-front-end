<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\competencyType;
class CreateCompetencyTypesTable extends Migration
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
        Schema::create('competency_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('definition', 10000);
            $table->softDeletes();
            $table->timestamps();
        });

         //create types 
         $core = new competencyType;
         $core->name = "CORE";
         $core->definition = "No definition";
         $core->save();

           //create types 
           $support = new competencyType;
           $support->name = "SUPPORT";
           $support->definition = "No definition";
           $support->save();

             //create types 
         $developmental = new competencyType;
         $developmental->name = "DEVELOPMENTAL";
         $developmental->definition = "No definition";
         $developmental->save();
  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competency_types');
    }
}
