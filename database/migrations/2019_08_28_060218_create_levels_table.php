<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\level;

class CreateLevelsTable extends Migration
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
        Schema::create('levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('weight');
            $table->string('definition', 10000);

            $table->softDeletes();

            $table->timestamps();
        });


        //create level 0
        $levelZero = new level;
        $levelZero->name = "I have no experience in the behaviors described";
        $levelZero->weight = "0";
        $levelZero->definition = "";
        $levelZero->save();
 
        //create level 1
        $levelOne = new level;
        $levelOne->name = "Implement / Operate";
        $levelOne->weight = "1";
        $levelOne->definition = "";
        $levelOne->save();

        //create level 2
        $levelTwo = new level;
        $levelTwo->name = "Advanced Application / Supervision";
        $levelTwo->weight = "2";
        $levelTwo->definition = "";
        $levelTwo->save();

        //create level 3
        $levelThree = new level;
        $levelThree->name = "Consult / Functional or Project Management";
        $levelThree->weight = "3";
        $levelThree->definition = "";
        $levelThree->save();

        //create level 4
        $levelFour = new level;
        $levelFour->name = "Advise / Influence";
        $levelFour->weight = "4";
        $levelFour->definition = "";
        $levelFour->save();

        //create level 5
        $levelFive = new level;
        $levelFive->name = "Set Strategy / Mobilize";
        $levelFive->weight = "5";
        $levelFive->definition = "";
        $levelFive->save();
 
        
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
