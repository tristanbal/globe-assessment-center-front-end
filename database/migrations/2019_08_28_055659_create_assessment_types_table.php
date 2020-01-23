<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\assessmentType;

class CreateAssessmentTypesTable extends Migration
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
        Schema::create('assessment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('relationshipID');
            $table->softDeletes();
            $table->timestamps();
        });

        $selfAssessment = new assessmentType;
        $selfAssessment->name = 'Self-Assessment';
        $selfAssessment->relationshipID = 1;
        $selfAssessment->save();

        $supervisorAssessment = new assessmentType;
        $supervisorAssessment->name = 'Supervisor Assessment';
        $supervisorAssessment->relationshipID = 2;
        $supervisorAssessment->save();

        $directAssessment = new assessmentType;
        $directAssessment->name = 'Direct Reporting Assessment';
        $directAssessment->relationshipID = 3;
        $directAssessment->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_types');
    }
}
