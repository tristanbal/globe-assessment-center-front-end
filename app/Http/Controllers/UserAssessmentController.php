<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee_data;
use App\assessmentType;
use App\relationship;
use App\employeeRelationship;
use App\role;
use App\group;
use App\listOfCompetenciesPerRole;
use App\competency;
use App\competencyType;
use App\level;
use App\proficiency;
use App\assessment;
use App\evaluation;
use App\evaluationCompetency;
use DB;

use \stdClass;

class UserAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->whereNull('deleted_at')->where('is_active','<>',0)->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();
        $user = employee_data::find(auth()->user()->employeeID);
        $employee = employee_data::whereNull('deleted_at')->where('roleID','<>',3)->where('roleID','<>',8)->get();
        $employeeRelationshipList = employeeRelationship::where('assessorEmployeeID',$employeeInfo->id)->where('is_active','<>',0)->whereNull('deleted_at')->get();
        $role = role::whereNull('deleted_at')->get();

        //  return $employeeRelationship;
        //return $employee;
        return view('user.assessment-index')->with(compact('employeeInfo','user','employeeRelationship','relationship','assessmentType','employee','employeeRelationshipList','role'));
    }

    public function assessmentStartUp($type,$employeeID){
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();
        
        $assessmentTypeUser = assessmentType::find($type);
        $model = null;
        $modelInfo = null;
        $modelGroup = null;
        $modelStart = null;

        //Assesee Information
        $assesseeEmployeeID = employee_data::find($employeeID);
        $assesseeRole = role::find($assesseeEmployeeID->roleID);
        //$model = listOfCompetenciesPerRole::where('roleID',$assesseeRole->id)->whereNull('deleted_at')->orderBy('id')->get();
        $model = listOfCompetenciesPerRole::where('roleID',$assesseeRole->id)->whereNull('deleted_at')->orderBy('competencyTypeID', 'ASC')->orderBy('competencyID', 'ASC')->get();
        $modelStart = $model->first();

        if($model == null){
            $modelInfo = listOfCompetenciesPerRole::where('roleID',$assesseeRole->id)->whereNull('deleted_at')->first();
            $modelGroup = group::find($modelInfo->groupID);
            
        }

        //Assessor Information
        $assessorEmployeeID = employee_data::find($employeeInfo->id);
        $assessorRole = role::find($assessorEmployeeID->roleID);

        return view('user.assessment-form')->with(compact('employeeRelationship','relationship','assessmentType',
            'assessmentTypeUser','model','modelInfo','modelGroup',
            'assesseeEmployeeID','assesseeRole',
            'assessorEmployeeID','assessorRole',
            'modelStart', 'employeeInfo'
        ));
    }

    public function assessmentItem($type,$employeeID,$modelID)
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();
        
        $assessmentTypeUser = assessmentType::find($type);
        $model = null;

        //Assesee Information
        $assesseeEmployeeID = employee_data::find($employeeID);
        $assesseeRole = role::find($assesseeEmployeeID->roleID);
        $modelItem = listOfCompetenciesPerRole::where('roleID',$assesseeRole->id)->where('id',$modelID)->whereNull('deleted_at')->first();

        $competencyItem = competency::find($modelItem->competencyID);
        $competencyItemType = competencyType::find($modelItem->competencyTypeID);
        //Assessor Information
        $assessorEmployeeID = employee_data::find($employeeInfo->id);
        $assessorRole = role::find($assessorEmployeeID->roleID);


        $proficiency = proficiency::where('competencyID','=',$competencyItem->id)->orderBy('levelID','ASC')->get();

        //return $proficiency;
        $proficiencyItem = array();

        $level = level::all();

        for($i=0;$i<count($proficiency);$i++){
            $proficiencyItemRow = new stdClass;

            $proficiencyItemRow->id = $proficiency[$i]->levelID-1;
            /*$levelID = level::select('name')->where('id','=',$proficiency[$i]->levelID)->first();
            $proficiencyItemRow->dataValue = $levelID->id;*/
            $proficiencyItemRow->dataValue = $proficiency[$i]->levelID;
            $levelName = level::select('name')->where('id','=',$proficiency[$i]->levelID)->first();
            $proficiencyItemRow->levelName = $levelName->name;
            $proficiencyItemRow->definition = $proficiency[$i]->definition;

            array_push($proficiencyItem,$proficiencyItemRow);             
        }

       // return $proficiencyItem;
        $zeroProficiency = new stdClass;
        
        $zeroProficiency->id = 0;
        $zeroProficiency->dataValue = 1 ;
        $zeroProficiency->levelName = 'Not Applicable';
        $zeroProficiency->definition = 'I have no experience in this before described.';

        array_push($proficiencyItem,$zeroProficiency);
        shuffle($proficiencyItem);

        //CHECK FOR ASSESSMENT ANSWER
        $userStoredEvaluationCompetency = null;

        $userStoredAssessment = assessment::where('employeeID',$assesseeEmployeeID->id)
            ->where('assessmentTypeID',$assessmentTypeUser->id)
            ->whereNull('deleted_at')->first();

        $userStoredEvaluation = null;
        if($userStoredAssessment){
            $userStoredEvaluation = evaluation::where('assessmentID',$userStoredAssessment->id)
                ->where('id',$userStoredAssessment->evaluationVersionID)
                ->where('assesseeEmployeeID',$assesseeEmployeeID->id)
                ->where('assessorEmployeeID',$assessorEmployeeID->id)
                ->where('assesseeRoleID',$assesseeEmployeeID->roleID)
                ->orderBy('created_at','desc')
                ->whereNull('deleted_at')
                ->first();
                //return $userStoredEvaluation;
            if($userStoredEvaluation){
                $userStoredEvaluationCompetency = evaluationCompetency::where('evaluationID',$userStoredEvaluation->id)
                ->where('competencyID',$competencyItem->id)
                ->where('competencyTypeID',$competencyItemType->id)
                ->where('targetLevelID',$modelItem->targetLevelID)
                ->whereNull('deleted_at')
                ->first();
            }
        }


        //COUNTER AND COMPLETION
        $listOfCompetenciesPerRoles = listOfCompetenciesPerRole::where('roleID',$assesseeRole->id)->whereNull('deleted_at')->orderBy('competencyTypeID', 'ASC')->orderBy('competencyID', 'ASC')->get();
        $assessmentItems = array();
        $isFirstPage = false;
        $isLastPage = false;
        $current_page = 0;
        $previous_page = 0;
        $next_page = 0;

        
        //return $listOfCompetenciesPerRoles[0];
        for($i=0;$i<count($listOfCompetenciesPerRoles);$i++){
            $modelItemRow = new stdClass();

            $modelItemRow->id = $i;
            $modelItemRow->modelRowID = $listOfCompetenciesPerRoles[$i]->id;
            
            $modelItemRow->answered = 0;
            if($userStoredEvaluation != null ){
                $userStoredEvaluationCompetencyChecker = evaluationCompetency::where('evaluationID',$userStoredEvaluation->id)
                ->where('competencyID',$listOfCompetenciesPerRoles[$i]->competencyID)
                //->where('targetLevelID',$modelItem->targetLevelID)
                ->whereNull('deleted_at')
                ->first();
                
                if($userStoredEvaluationCompetencyChecker){
                    $modelItemRow->answered = 1;
                }
            }
            
            
            if($modelItemRow->modelRowID==$modelID){
                if(count($listOfCompetenciesPerRoles) == 1){
                    $isFirstPage = true;
                    $isLastPage = true;
                }elseif(count($listOfCompetenciesPerRoles) > 1){
                    if($i > 0 && $i < count($listOfCompetenciesPerRoles)-1){
                        $isFirstPage = false;
                        $isLastPage = false;
                    } elseif($i == 0) {
                        $isFirstPage = true;
                    } elseif($i == count($listOfCompetenciesPerRoles)-1){
                        $isLastPage = true;
                        //return 'test';
                    } 
                    $current_page = $i;
                }
                
            }

            array_push($assessmentItems,$modelItemRow);
        }

        
        if($isLastPage == false && $isFirstPage == false ){
            $previous_page = $assessmentItems[$current_page-1]->modelRowID;
            $next_page = $assessmentItems[$current_page+1]->modelRowID;
        }elseif($isLastPage == true && $isFirstPage == false){
            $previous_page = $assessmentItems[$current_page-1]->modelRowID;
        }elseif($isLastPage == false && $isFirstPage == true){
            $next_page = $assessmentItems[$current_page+1]->modelRowID;
        }elseif($isLastPage == true && $isFirstPage == true){
        }

        // /return $assessmentItems;
        

        return view('user.assessment-item')->with(compact('employeeRelationship','relationship','assessmentType',
            'assessmentTypeUser','model','modelItem','competencyItem','competencyItemType',
            'assesseeEmployeeID','assesseeRole',
            'assessorEmployeeID','assessorRole',
            'modelStart','proficiencyItem','userStoredEvaluationCompetency','assessmentItems',
            'previous_page','next_page','isFirstPage','isLastPage','userStoredEvaluation','employeeInfo'
        ));
    }

    //public function addEval($type,$employeeID,$modelID,Request $request)
    public function addEval(Request $request)
    {
        
        // INPUT PRE-REQ VALUES
        $assesseeEmployee = employee_data::find($request->input('assesseeEmployeeID'));
        $assessorEmployee = employee_data::find($request->input('assessorEmployeeID'));
        $assessmentType = assessmentType::find($request->input('assessmentTypeID'));
        
        $givenLevelID = level::find($request->input('givenLevelID'));
        $modelItem = listOfCompetenciesPerRole::where('id',$request->input('modelCompetencyID'))->first();
        
        $assessmentResult = assessment::where('employeeID',$assesseeEmployee->id)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        if($assessmentResult){
            $initAssessment = assessment::where('employeeID',$assesseeEmployee->id)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        }else{
            
            $assessmentNew = new assessment;
            $assessmentNew->employeeID = $assesseeEmployee->id;
            $assessmentNew->evaluationVersionID = 0;
            $assessmentNew->assessmentTypeID = $assessmentType->id;
            $assessmentNew->save();

            $initAssessment = assessment::where('employeeID',$assesseeEmployee->employeeID)->where('evaluationVersionID',0)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        }

        if($initAssessment->evaluationVersionID == 0){
            $evaluationNew = new evaluation;
            $evaluationNew->assessmentID = $initAssessment->id;
            $evaluationNew->assesseeEmployeeID = $assesseeEmployee->id;
            $evaluationNew->assessorEmployeeID = $assessorEmployee->id;
            $evaluationNew->assesseeRoleID = $assesseeEmployee->roleID;
            $evaluationNew->assessorRoleID = $assessorEmployee->roleID;
            $evaluationNew->save();

            $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                ->where('assesseeEmployeeID',$assesseeEmployee->id)
                ->where('assessorEmployeeID',$assessorEmployee->id)
                ->where('assesseeRoleID',$assesseeEmployee->roleID)
                ->orderBy('created_at','desc')
                ->whereNull('deleted_at')
                ->first();
            
            $assessmentUpdate = assessment::find($initAssessment->id);
            $assessmentUpdate->evaluationVersionID = $initEvaluation->id;
            $assessmentUpdate->save();
            $sample = 'updated';
        }else{
            $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                ->where('assesseeEmployeeID',$assesseeEmployee->id)
                ->where('assessorEmployeeID',$assessorEmployee->id)
                ->where('assesseeRoleID',$assesseeEmployee->roleID)
                ->where('id',$initAssessment->evaluationVersionID)
                ->whereNull('deleted_at')
                ->first();
            $sample = 'no change';

            if ($initEvaluation) {
                //success
            } else {
                $evaluationNewRole = new evaluation;
                $evaluationNewRole->assessmentID = $initAssessment->id;
                $evaluationNewRole->assesseeEmployeeID = $assesseeEmployee->id;
                $evaluationNewRole->assessorEmployeeID = $assessorEmployee->id;
                $evaluationNewRole->assesseeRoleID = $assesseeEmployee->roleID;
                $evaluationNewRole->assessorRoleID = $assessorEmployee->roleID;
                $evaluationNewRole->save();

                $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                    ->where('assesseeEmployeeID',$assesseeEmployee->id)
                    ->where('assessorEmployeeID',$assessorEmployee->id)
                    ->where('assesseeRoleID',$assesseeEmployee->roleID)
                    ->orderBy('created_at','desc')
                    ->whereNull('deleted_at')
                    ->first();
                
                $assessmentUpdate = assessment::find($initAssessment->id);
                $assessmentUpdate->evaluationVersionID = $initEvaluation->id;
                $assessmentUpdate->save();
            }
            
        }


        $evaluationCompetencyItem = evaluationCompetency::where('evaluationID',$initEvaluation->id)
            ->where('competencyID',$modelItem->competencyID)
            ->where('competencyTypeID',$modelItem->competencyTypeID)
            ->where('targetLevelID',$modelItem->targetLevelID)
            ->whereNull('deleted_at')
            ->first();

        if($evaluationCompetencyItem){
            $updateEvaluationCompetency = evaluationCompetency::find($evaluationCompetencyItem->id);
            $updateEvaluationCompetency->givenLevelID = $givenLevelID->id;
            $updateEvaluationCompetency->weightedScore = $givenLevelID->weight;
            $updateEvaluationCompetency->save();
            
            $text = 'Assessment Updated';
        }else {
            $evaluationCompetencyNew = new evaluationCompetency;
            $evaluationCompetencyNew->evaluationID = $initEvaluation->id;
            $evaluationCompetencyNew->competencyID = $modelItem->competencyID;
            $evaluationCompetencyNew->givenLevelID = $givenLevelID->id;
            $evaluationCompetencyNew->targetLevelID = $modelItem->targetLevelID;
            $evaluationCompetencyNew->weightedScore = $givenLevelID->weight;
            $evaluationCompetencyNew->competencyTypeID = $modelItem->competencyTypeID;
            $evaluationCompetencyNew->verbatim = "N/A";
            $evaluationCompetencyNew->additional_file = "N/A";
            $evaluationCompetencyNew->save();
            $text = 'Assessment Answered';
        }

        $test = $request->input('givenLevelID');
        return response()->json(['message'=>$text]);
    }

    public function assessmentEnd($type,$employeeID)
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();
        
        $assessmentTypeUser = assessmentType::find($type);
        $assesseeEmployeeID = employee_data::find($employeeID);

        //CHECK FOR ASSESSMENT ANSWER
        $userStoredEvaluationCompetency = null;

        $userStoredAssessment = assessment::where('employeeID',$assesseeEmployeeID->id)->where('assessmentTypeID',$assessmentTypeUser->id)->whereNull('deleted_at')->first();
        if($userStoredAssessment){
            $userStoredEvaluation = evaluation::find($userStoredAssessment->evaluationVersionID);
            //$userStoredEvaluation = evaluation::where('assessmentID',$userStoredAssessment->id)->where('assesseeEmployeeID',$assesseeEmployeeID->id)->where('assessorEmployeeID',$employeeInfo->id)->whereNull('deleted_at')->first();
            if($userStoredEvaluation){
                $userStoredEvaluationCompetency = evaluationCompetency::where('evaluationID',$userStoredEvaluation->id)
                ->whereNull('deleted_at')
                ->get();
                //return $userStoredEvaluationCompetency;
            }
        }

        
        //CHECK FOR MODEL
        $model = null;
        $model_unanswered = null;

        $model = listOfCompetenciesPerRole::where('roleID',$assesseeEmployeeID->roleID)->whereNull('deleted_at')->get();
        
        //COMPLETION CHECKER
        // /return $userStoredEvaluationCompetency;
        $completionChekerArray = array();
        if ($model){
            if ($userStoredEvaluationCompetency) {
                foreach($model as $modelItem){
                    foreach($userStoredEvaluationCompetency as $userStoredEvaluationCompetencyItem){
                        if($modelItem->competencyID == $userStoredEvaluationCompetencyItem->competencyID){
                            $competencyItem = new stdClass();
                            $competencyItem->competencyID = $modelItem->competencyID;
                            
                            array_push($completionChekerArray,$competencyItem);
                        }
                    }
                }
            }
            
        }

        return view('user.assessment-end')->with(compact('employeeRelationship','relationship','assessmentType',
            'assessmentTypeUser','assesseeEmployeeID',
            'model','completionChekerArray','employeeInfo'
        ));
    }

    public function addVerbatim($type,$employeeID,$modelItem,Request $request)
    {

        if($request->input("verbatim") == ""){
            return redirect()->back()->with('error', 'Submitted verbatim cannot be null.');
        }

        $assesseeEmployee = employee_data::find($employeeID);
        $assessorEmployee = employee_data::find(auth()->user()->employeeID);
        $assessmentType = assessmentType::find($type);

        //return 'test';
        $assessmentResult = assessment::where('employeeID',$assesseeEmployee->id)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        
        if($assessmentResult){
            $initAssessment = assessment::where('employeeID',$assesseeEmployee->id)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        }else{
            
            $assessmentNew = new assessment;
            $assessmentNew->employeeID = $assesseeEmployee->id;
            $assessmentNew->evaluationVersionID = 0;
            $assessmentNew->assessmentTypeID = $assessmentType->id;
            $assessmentNew->save();

            $initAssessment = assessment::where('employeeID',$assesseeEmployee->id)->where('evaluationVersionID',0)->where('assessmentTypeID',$assessmentType->id)->whereNull('deleted_at')->first();
        }

        if($initAssessment->evaluationVersionID == 0){
            $evaluationNew = new evaluation;
            $evaluationNew->assessmentID = $initAssessment->id;
            $evaluationNew->assesseeEmployeeID = $assesseeEmployee->id;
            $evaluationNew->assessorEmployeeID = $assessorEmployee->id;
            $evaluationNew->assesseeRoleID = $assesseeEmployee->roleID;
            $evaluationNew->assessorRoleID = $assessorEmployee->roleID;
            $evaluationNew->save();

            $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                ->where('assesseeEmployeeID',$assesseeEmployee->id)
                ->where('assessorEmployeeID',$assessorEmployee->id)
                ->where('assesseeRoleID',$assesseeEmployee->roleID)
                ->orderBy('created_at','desc')->whereNull('deleted_at')
                ->first();
            
            $assessmentUpdate = assessment::find($initAssessment->id);
            $assessmentUpdate->evaluationVersionID = $initEvaluation->id;
            $assessmentUpdate->save();
            $sample = 'updated';
        }else{
            $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                ->where('assesseeEmployeeID',$assesseeEmployee->id)
                ->where('assessorEmployeeID',$assessorEmployee->id)
                ->where('assesseeRoleID',$assesseeEmployee->roleID)
                ->where('id',$initAssessment->evaluationVersionID)
                ->whereNull('deleted_at')
                ->first();
            $sample = 'no change';

            if ($initEvaluation) {
                //success
            } else {
                $evaluationNewRole = new evaluation;
                $evaluationNewRole->assessmentID = $initAssessment->id;
                $evaluationNewRole->assesseeEmployeeID = $assesseeEmployee->id;
                $evaluationNewRole->assessorEmployeeID = $assessorEmployee->id;
                $evaluationNewRole->assesseeRoleID = $assesseeEmployee->roleID;
                $evaluationNewRole->assessorRoleID = $assessorEmployee->roleID;
                $evaluationNewRole->save();

                $initEvaluation = evaluation::where('assessmentID',$initAssessment->id)
                    ->where('assesseeEmployeeID',$assesseeEmployee->id)
                    ->where('assessorEmployeeID',$assessorEmployee->id)
                    ->where('assesseeRoleID',$assesseeEmployee->roleID)
                    ->where('assessorRoleID',$assessorEmployee->roleID)
                    ->orderBy('created_at','desc')
                    ->whereNull('deleted_at')
                    ->first();
                
                $assessmentUpdate = assessment::find($initAssessment->id);
                $assessmentUpdate->evaluationVersionID = $initEvaluation->id;
                $assessmentUpdate->save();
            }
        }

        $loadedItem = listOfCompetenciesPerRole::find($modelItem);
        $evaluationCompetencyItem = evaluationCompetency::where('evaluationID',$initEvaluation->id)
            ->where('competencyID',$loadedItem->competencyID)
            ->where('competencyTypeID',$loadedItem->competencyTypeID)
            ->where('targetLevelID',$loadedItem->targetLevelID)
            ->whereNull('deleted_at')
            ->first();
            

        if($evaluationCompetencyItem){
            $updateEvaluationCompetency = evaluationCompetency::find($evaluationCompetencyItem->id);
            $updateEvaluationCompetency->verbatim = $request->input('verbatim');
            $updateEvaluationCompetency->save();
            
            $text = 'Assessment Updated';
        }else {
            $evaluationCompetencyNew = new evaluationCompetency;
            $evaluationCompetencyNew->evaluationID = $initEvaluation->id;
            $evaluationCompetencyNew->competencyID = $loadedItem->competencyID;
            $evaluationCompetencyNew->givenLevelID = 1;
            $evaluationCompetencyNew->targetLevelID = $loadedItem->targetLevelID;
            $evaluationCompetencyNew->weightedScore = 0;
            $evaluationCompetencyNew->competencyTypeID = $loadedItem->competencyTypeID;
            $evaluationCompetencyNew->verbatim = $request->input('verbatim');
            $evaluationCompetencyNew->additional_file = "N/A";
            $evaluationCompetencyNew->save();
            $text = 'Assessment Answered';
        }
        
        return redirect()->back()->with('success', 'Verbatim Saved.');
    }

    public function addAttachment($type,$employeeID,$modelItem,Request $request)
    {
        return redirect()->back()->with('success', ['Saved.']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
