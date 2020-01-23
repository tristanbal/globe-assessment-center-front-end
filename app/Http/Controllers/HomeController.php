<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee_data;
use App\assessment;
use App\evaluation;
use App\evaluationCompetency;
use App\assessmentType;
use App\competencyType;
use App\relationship;
use App\employeeRelationship;
use App\listOfCompetenciesPerRole;
use App\group;
use App\division;
use App\department;
use App\section;
use App\role;
use App\job;
use App\band;
use App\gapAnalysisSetting;
use App\gapAnalysisSettingAssessmentType;
use App\completionTrackerAssignment;
use App\groupsPerGapAnalysisSetting;
use App\groupsPerGapAnalysisSettingRole;
use App\Exports\completionTrackerSummaryExport;
use App\Exports\completionTrackerBreakdownExport;
use Maatwebsite\Excel\Facades\Excel;

use \stdClass;
ini_set('max_execution_time', 12000);
ini_set('memory_limit', '4095M');


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();

        $completionTrackerAssignment = completionTrackerAssignment::where('employeeID',$employeeInfo->id)->whereNull('deleted_at')->get();
        //return $employeeRelationship;
        $user = employee_data::find(auth()->user()->employeeID);
        
        return view('home')->with(compact('user','employeeRelationship','relationship','assessmentType','employeeInfo','completionTrackerAssignment'));
    }

    public function completionTracker($id)
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();

        $completionTrackerAssignment = completionTrackerAssignment::find($id);
        
        $groupsPerGapAnalysisSettingRole = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->get();

        $groupsPerGapAnalysisSettingRoleModel = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->first();
        
        $modelGroup = listOfCompetenciesPerRole::where('roleID',$groupsPerGapAnalysisSettingRoleModel->roleID)->first();
        $group = group::find($modelGroup->groupID);
        //return $group;

        $roles_selected_id = null;
        $roles_selected = array();
        $roles_name = array();
        foreach($groupsPerGapAnalysisSettingRole as $modelBasisItem){
            array_push($roles_selected,strval($modelBasisItem->roleID));

            $specificRowItem = role::find($modelBasisItem->roleID);
            array_push($roles_name,$specificRowItem->name);
        }
        foreach($groupsPerGapAnalysisSettingRole as $item){
            $roles_selected_id .= $item->roleID.',';
        }
        
        $role = role::whereNull('deleted_at')->get();
        $assessmentTypeTracker = assessmentType::whereNull('deleted_at')->get();
        $competencyTypeTracker = competencyType::whereNull('deleted_at')->get();
        $employeeTracker = employee_data::whereNull('deleted_at')->get();
        $employeeRelationshipTracker = employeeRelationship::whereNull('deleted_at')->get();
        $relationshipTracker = relationship::whereNull('deleted_at')->get();

        //return $roles_selected;
        $arraySelection = array();

        $arrayBreakdown = array();
        //return $completionTrackerAssignment->gpgas_id_foreign;

        $groupsPerGapAnalysisSetting = groupsPerGapAnalysisSetting::find($completionTrackerAssignment->gpgas_id_foreign);
        $gapAnalysisSetting = gapAnalysisSetting::find($groupsPerGapAnalysisSetting->gapAnalysisSettingID);
        if($gapAnalysisSetting){
            if($roles_selected){
                foreach($roles_selected as $rsItem){
                    foreach($role as $roleItem){
                        if($rsItem == $roleItem->id){
                            if($assessmentTypeTracker){
                                foreach($assessmentTypeTracker as $assessmentTypeItem){
                                    
                                    
                                    $countTarget = 0;
    
                                    $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                    
    
                                    //Memory Efficient
                                    for($i=0;$i<count($targetEmployees);$i++){
                                        $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                            ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                            ->where('is_active',1)
                                            ->whereNull('deleted_at')
                                            ->first();
                                        if($employeeRelationshipTracker){
                                            $countTarget++;
                                        }
                                    }
                                    
                                    if ($countTarget > 0) {
                                        $arrayRow = new stdClass();
                                        $arrayRow->roleID = $roleItem->id;
                                        $arrayRow->roleName = $roleItem->name;
                                        $arrayRow->assessmentTypeID = $assessmentTypeItem->id;
                                        $arrayRow->assessmentType = $assessmentTypeItem->name;
                                        $arrayRow->target = $countTarget;
    
                                        $countAssessed = 0;
    
                                        $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                        $countModel = count($model);
                                        //Memory Efficient 
                                        for($i=0;$i<count($targetEmployees);$i++){
                                            $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                                ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                                ->where('is_active',1)
                                                ->whereNull('deleted_at')
                                                ->first();
                                            if($employeeRelationshipTracker){
                                                $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTracker->assesseeEmployeeID)->where('assessmentTypeID',$assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                                if($assessmentTracker){
                                                    $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                    if($evaluationTracker){
                                                        $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                        if($evaluationCompetency){
                                                            $model_evaluation_counter = 0;
                                                            foreach ($model as $modelItem) {
                                                                $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                if ($evaluationChecker) {
                                                                    $model_evaluation_counter++;
                                                                }
                                                            }
                                                            if($model_evaluation_counter == $countModel){
                                                                $countAssessed++;
                                                            }
                                                        }
                                                    } 
                                                }
                                            }
                                        }
    
                                        $arrayRow->assessed = $countAssessed;
    
                                        $arrayRow->completion = 0;
                                        if ($countAssessed > 0 && $countTarget > 0 ) {
                                            $arrayRow->completion = ($countAssessed / $countTarget) * 100;
                                        } 
                                        array_push($arraySelection,$arrayRow);
                                    }
                                    
                                }
                                //Both
                                $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->whereNull('deleted_at')->get();
                                
                                $assessmentBothEmployees = array();
                                
                                $countGapAnalysisSettingAssessmentType = count($gapAnalysisSettingAssessmentType);
                                
                                $subtotalAssessmentBothCount = 0;
                                $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                
                                foreach($targetEmployees as $targetEmployeesItem){
                                    $employeeRelationshipTracker = employeeRelationship::where('assesseeEmployeeID',$targetEmployeesItem->id)
                                        ->where('is_active',1)
                                        ->whereNull('deleted_at')
                                        ->get();
                                    
                                        //return $employeeRelationshipTracker;
                                    if($employeeRelationshipTracker){
                                        $totalBothCount = 0;
                                        foreach($employeeRelationshipTracker as $employeeRelationshipTrackerItem){
                                            foreach($gapAnalysisSettingAssessmentType as $gapAnalysisSettingAssessmentTypeItem){
                                                if ($gapAnalysisSettingAssessmentTypeItem->assessmentTypeID == $employeeRelationshipTrackerItem->relationshipID ) {
                                                    $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTrackerItem->assesseeEmployeeID)->where('assessmentTypeID',$gapAnalysisSettingAssessmentTypeItem->assessmentTypeID)->whereNull('deleted_at')->first();
                                                    //echo $assessmentTracker;
                                                    if($assessmentTracker){
                                                        $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                        if($evaluationTracker){
                                                            $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                            if($evaluationCompetency){
                                                                $model_evaluation_counter = 0;
                                                                foreach ($model as $modelItem) {
                                                                    $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                    if ($evaluationChecker) {
                                                                        $model_evaluation_counter++;
                                                                    }
                                                                }
                                                                if($model_evaluation_counter == $countModel){
                                                                    $totalBothCount++;
                                                                }
                                                            }
                                                        } 
                                                    }
                                                    if($totalBothCount == $countGapAnalysisSettingAssessmentType){
                                                        $subtotalAssessmentBothCount++;
                                                        
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                }
                                $arrayRowBoth = new stdClass();
                                $arrayRowBoth->roleID = $roleItem->id;
                                $arrayRowBoth->roleName = $roleItem->name;
                                $arrayRowBoth->assessmentTypeID = 0;
                                $arrayRowBoth->assessmentType = 'Both Assessment';
                                $arrayRowBoth->target = count($targetEmployees);
                                $arrayRowBoth->assessed = $subtotalAssessmentBothCount;
                                //return $subtotalAssessmentBothCount;
                                $arrayRowBoth->completion = 0;
                                if ($subtotalAssessmentBothCount > 0 && count($targetEmployees) > 0 ) {
                                    $arrayRowBoth->completion = ($subtotalAssessmentBothCount / count($targetEmployees)) * 100;
                                } 
                                array_push($arraySelection,$arrayRowBoth);
                            }
                        }
                    }
                }
            }
        }

        if($roles_selected){
            foreach($roles_selected as $rsItem){
                foreach($role as $roleItem){
                    if($rsItem == $roleItem->id){
                        if($assessmentTypeTracker){
                            foreach($assessmentTypeTracker as $assessmentTypeItem){

                                $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();

                                $selectedAssessmentType = assessmentType::where('relationshipID', $assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                $countModel = count($model);

                                for($i=0;$i<count($targetEmployees);$i++){
                                    $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                        ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                        ->where('relationshipID',$selectedAssessmentType->relationshipID)
                                        ->where('is_active',1)
                                        ->whereNull('deleted_at')
                                        ->first();

                                    if ($employeeRelationshipTracker) {
                                        $arrayRow = new stdClass();
                                        $arrayRow->roleID = $roleItem->id;
                                        $arrayRow->roleName = $roleItem->name;
                                        $arrayRow->assessmentTypeID = $assessmentTypeItem->id;
                                        $arrayRow->assessmentType = $assessmentTypeItem->name;
                                        
                                        $assessee = employee_data::where('id',$employeeRelationshipTracker->assesseeEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assesseeEmployeeID = $assessee->employeeID;
                                        $arrayRow->assesseeName = $assessee->firstname . ' ' . $assessee->lastname;
    
                                        $assessor = employee_data::where('id',$employeeRelationshipTracker->assessorEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assessorEmployeeID = $assessor->employeeID;
                                        $arrayRow->assessorName = $assessor->firstname . ' ' . $assessor->lastname;
                                        $arrayRow->completion = 0; 

                                        $assessmentTracker = assessment::where('employeeID',$assessee->id)->where('assessmentTypeID',$assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                        if($assessmentTracker){
                                            $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                            if($evaluationTracker){
                                                $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                if($evaluationCompetency){
                                                    $model_evaluation_counter = 0;
                                                    foreach ($model as $modelItem) {
                                                        $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                        if ($evaluationChecker) {
                                                            $model_evaluation_counter++;
                                                        }
                                                    }
                                                    if($model_evaluation_counter == $countModel){
                                                        $arrayRow->completion = 1; 
                                                    }elseif($model_evaluation_counter <= $countModel && $model_evaluation_counter >0){
                                                        $arrayRow->completion = 2; 
                                                    }
                                                }
                                            } 
                                        }
                                        array_push($arrayBreakdown,$arrayRow);
                                    }
                                    
                                }
                                
                            }
                        }
                    }
                }
            }
        }



        $finalTargetTotal = 0;
        $finalAssessedTotal = 0;
        $finalPercentageTotal = 0;
        if($gapAnalysisSetting){
            if($roles_selected){
                foreach($roles_selected as $rsItem){
                    $roleItem = role::find($rsItem);
                    if($roleItem){
                        if($assessmentTypeTracker){
                            //Both
                            $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                            $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->whereNull('deleted_at')->get();
                            
                            $countModel = count($model);
                            $assessmentBothEmployees = array();
                            
                            $countGapAnalysisSettingAssessmentType = count($gapAnalysisSettingAssessmentType);
                            
                            $subtotalAssessmentBothCount = 0;
                            $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                            
                            foreach($targetEmployees as $targetEmployeesItem){
                                $employeeRelationshipTracker = employeeRelationship::where('assesseeEmployeeID',$targetEmployeesItem->id)
                                    ->where('is_active',1)
                                    ->whereNull('deleted_at')
                                    ->get();
                                
                                    //return $employeeRelationshipTracker;
                                if($employeeRelationshipTracker){
                                    $totalBothCount = 0;
                                    foreach($employeeRelationshipTracker as $employeeRelationshipTrackerItem){
                                        foreach($gapAnalysisSettingAssessmentType as $gapAnalysisSettingAssessmentTypeItem){
                                            if ($gapAnalysisSettingAssessmentTypeItem->assessmentTypeID == $employeeRelationshipTrackerItem->relationshipID ) {
                                                $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTrackerItem->assesseeEmployeeID)->where('assessmentTypeID',$gapAnalysisSettingAssessmentTypeItem->assessmentTypeID)->whereNull('deleted_at')->first();
                                                //echo $assessmentTracker;
                                                if($assessmentTracker){
                                                    $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                    if($evaluationTracker){
                                                        $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                        if($evaluationCompetency){
                                                            $model_evaluation_counter = 0;
                                                            foreach ($model as $modelItem) {
                                                                $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                if ($evaluationChecker) {
                                                                    $model_evaluation_counter++;
                                                                }
                                                            }
                                                            if($model_evaluation_counter == $countModel){
                                                                $totalBothCount++;
                                                            }
                                                        }
                                                    } 
                                                }
                                                if($totalBothCount == $countGapAnalysisSettingAssessmentType){
                                                    $subtotalAssessmentBothCount++;
                                                    
                                                } 
                                            }
                                        }
                                    }
                                }
                            }
                            $finalTargetTotal = $finalTargetTotal + count($targetEmployees);
                            $finalAssessedTotal = $subtotalAssessmentBothCount + $finalAssessedTotal;
                        }
                    }
                }
            }
        }

        if($finalTargetTotal>0 && $finalAssessedTotal >0){
            $finalPercentageTotal = ($finalAssessedTotal/$finalTargetTotal)*100;
        }

        $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->whereNull('deleted_at')->get();


        return view('user.completion-tracker')->with(compact(
            'roles_name',
            'finalTargetTotal',
            'finalAssessedTotal',
            'finalPercentageTotal',
            'id',
            'group',
            'roles_selected',
            'role',
            'assessmentType',
            'assessment',
            'evaluation',
            'evaluationCompetency',
            'competencyType',
            'employee',
            'arraySelection',
            'arrayBreakdown',
            'roles_selected_id',
            'gapAnalysisSettingAssessmentType',
            'employeeRelationship','relationship','assessmentType','employeeInfo','completionTrackerAssignment'
        ));
 
    }

    public function overallCompletionTracker($id)
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();

        $completionTrackerAssignment = completionTrackerAssignment::find($id);
        $groupsPerGapAnalysisSettingRole = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->get();

        $groupsPerGapAnalysisSettingRoleModel = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->first();
        
        $modelGroup = listOfCompetenciesPerRole::where('roleID',$groupsPerGapAnalysisSettingRoleModel->roleID)->first();
        $group = group::find($modelGroup->groupID);

        
        $roles_selected_id = null;
        $roles_selected = array();
        foreach($groupsPerGapAnalysisSettingRole as $modelBasisItem){
            array_push($roles_selected,strval($modelBasisItem->roleID));
        }
        foreach($groupsPerGapAnalysisSettingRole as $item){
            $roles_selected_id .= $item->roleID.',';
        }

        $role = role::whereNull('deleted_at')->get();
        $assessmentTypeTracker = assessmentType::whereNull('deleted_at')->get();
        $competencyTypeTracker = competencyType::whereNull('deleted_at')->get();
        $employeeTracker = employee_data::whereNull('deleted_at')->get();
        $employeeRelationshipTracker = employeeRelationship::whereNull('deleted_at')->get();
        $relationshipTracker = relationship::whereNull('deleted_at')->get();

        //return $roles_selected;
        $arraySelection = array();
        $gapAnalysisSetting = gapAnalysisSetting::find($completionTrackerAssignment->gpgas_id_foreign);
        $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->get();
        //return $gapAnalysisSettingAssessmentType;
        $finalTargetTotal = 0;
        $finalAssessedTotal = 0;
        $finalPercentageTotal = 0;
        if($gapAnalysisSetting){
            if($roles_selected){
                foreach($roles_selected as $rsItem){
                    $roleItem = role::find($rsItem);
                    if($roleItem){
                        if($assessmentTypeTracker){
                            //Both
                            $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                            $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->whereNull('deleted_at')->get();
                            
                            $countModel = count($model);
                            $assessmentBothEmployees = array();
                            
                            $countGapAnalysisSettingAssessmentType = count($gapAnalysisSettingAssessmentType);
                            
                            $subtotalAssessmentBothCount = 0;
                            $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                            
                            foreach($targetEmployees as $targetEmployeesItem){
                                $employeeRelationshipTracker = employeeRelationship::where('assesseeEmployeeID',$targetEmployeesItem->id)
                                    ->where('is_active',1)
                                    ->whereNull('deleted_at')
                                    ->get();
                                
                                    //return $employeeRelationshipTracker;
                                if($employeeRelationshipTracker){
                                    $totalBothCount = 0;
                                    foreach($employeeRelationshipTracker as $employeeRelationshipTrackerItem){
                                        foreach($gapAnalysisSettingAssessmentType as $gapAnalysisSettingAssessmentTypeItem){
                                            if ($gapAnalysisSettingAssessmentTypeItem->assessmentTypeID == $employeeRelationshipTrackerItem->relationshipID ) {
                                                $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTrackerItem->assesseeEmployeeID)->where('assessmentTypeID',$gapAnalysisSettingAssessmentTypeItem->assessmentTypeID)->whereNull('deleted_at')->first();
                                                //echo $assessmentTracker;
                                                if($assessmentTracker){
                                                    $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                    if($evaluationTracker){
                                                        $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                        if($evaluationCompetency){
                                                            $model_evaluation_counter = 0;
                                                            foreach ($model as $modelItem) {
                                                                $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                if ($evaluationChecker) {
                                                                    $model_evaluation_counter++;
                                                                }
                                                            }
                                                            if($model_evaluation_counter == $countModel){
                                                                $totalBothCount++;
                                                            }
                                                        }
                                                    } 
                                                }
                                                if($totalBothCount == $countGapAnalysisSettingAssessmentType){
                                                    $subtotalAssessmentBothCount++;
                                                    
                                                } 
                                            }
                                        }
                                    }
                                }
                            }
                            $finalTargetTotal = $finalTargetTotal + count($targetEmployees);
                            $finalAssessedTotal = $subtotalAssessmentBothCount + $finalAssessedTotal;
                        }
                    }
                }
            }
        }

        if($finalTargetTotal>0 && $finalAssessedTotal >0){
            $finalPercentageTotal = ($finalAssessedTotal/$finalTargetTotal)*100;
        }
        return $finalPercentageTotal;
        
        return view('user.completion-tracker-overall')->with(compact(
            'id',
            'group',
            'roles_selected',
            'role',
            'assessmentType',
            'assessment',
            'evaluation',
            'evaluationCompetency',
            'competencyType',
            'employee',
            'employeeRelationship','relationship','assessmentType','employeeInfo','completionTrackerAssignment'
        ));
    }

    public function export(Request $request,$id) 
    {

        $completionTrackerAssignment = completionTrackerAssignment::find($id);
        
        $groupsPerGapAnalysisSettingRole = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->get();

        $groupsPerGapAnalysisSettingRoleModel = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->first();
        
        $modelGroup = listOfCompetenciesPerRole::where('roleID',$groupsPerGapAnalysisSettingRoleModel->roleID)->first();
        $group = group::find($modelGroup->groupID);

        $roles_selected_id = null;
        $roles_selected = array();
        foreach($groupsPerGapAnalysisSettingRole as $modelBasisItem){
            array_push($roles_selected,strval($modelBasisItem->roleID));
        }
        foreach($groupsPerGapAnalysisSettingRole as $item){
            $roles_selected_id .= $item->roleID.',';
        }

        $role = role::whereNull('deleted_at')->get();
        $assessmentTypeTracker = assessmentType::whereNull('deleted_at')->get();
        $competencyTypeTracker = competencyType::whereNull('deleted_at')->get();
        $employeeTracker = employee_data::whereNull('deleted_at')->get();
        $employeeRelationshipTracker = employeeRelationship::whereNull('deleted_at')->get();
        $relationshipTracker = relationship::whereNull('deleted_at')->get();
        
        //return $roles_selected;
        $arraySelection = array();

        $arrayBreakdown = array();

        $gapAnalysisSetting = gapAnalysisSetting::find($completionTrackerAssignment->gpgas_id_foreign);
        if($gapAnalysisSetting){
            if($roles_selected){
                foreach($roles_selected as $rsItem){
                    foreach($role as $roleItem){
                        if($rsItem == $roleItem->id){
                            if($assessmentTypeTracker){
                                foreach($assessmentTypeTracker as $assessmentTypeItem){
                                    
                                    
                                    $countTarget = 0;
    
                                    $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                    
    
                                    //Memory Efficient
                                    for($i=0;$i<count($targetEmployees);$i++){
                                        $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                            ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                            ->where('is_active',1)
                                            ->whereNull('deleted_at')
                                            ->first();
                                        if($employeeRelationshipTracker){
                                            $countTarget++;
                                        }
                                    }
                                    
                                    if ($countTarget > 0) {
                                        $arrayRow = new stdClass();
                                        $arrayRow->roleName = $roleItem->name;
                                        $arrayRow->assessmentType = $assessmentTypeItem->name;
                                        $arrayRow->target = $countTarget;
    
                                        $countAssessed = 0;
    
                                        $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                        $countModel = count($model);
                                        //Memory Efficient 
                                        for($i=0;$i<count($targetEmployees);$i++){
                                            $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                                ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                                ->where('is_active',1)
                                                ->whereNull('deleted_at')
                                                ->first();
                                            if($employeeRelationshipTracker){
                                                $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTracker->assesseeEmployeeID)->where('assessmentTypeID',$assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                                if($assessmentTracker){
                                                    $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                    if($evaluationTracker){
                                                        $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                        if($evaluationCompetency){
                                                            $model_evaluation_counter = 0;
                                                            foreach ($model as $modelItem) {
                                                                $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                if ($evaluationChecker) {
                                                                    $model_evaluation_counter++;
                                                                }
                                                            }
                                                            if($model_evaluation_counter == $countModel){
                                                                $countAssessed++;
                                                            }
                                                        }
                                                    } 
                                                }
                                            }
                                        }
    
                                        if($countAssessed == 0){
                                            $arrayRow->assessed = 'N/A';
                                        }else {
                                            $arrayRow->assessed = $countAssessed;
                                        }
                                        
    
                                        $arrayRow->completion = 'N/A';
                                        if ($countAssessed > 0 && $countTarget > 0 ) {
                                            $arrayRow->completion = ($countAssessed / $countTarget) * 100;
                                        } 
                                        array_push($arraySelection,$arrayRow);
                                    }
                                    
                                }
                                //Both
                                $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                $gapAnalysisSettingAssessmentType = gapAnalysisSettingAssessmentType::where('gas_id_foreign',$gapAnalysisSetting->id)->whereNull('deleted_at')->get();
                                
                                $assessmentBothEmployees = array();
                                
                                $countGapAnalysisSettingAssessmentType = count($gapAnalysisSettingAssessmentType);
                                
                                $subtotalAssessmentBothCount = 0;
                                $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                
                                foreach($targetEmployees as $targetEmployeesItem){
                                    $employeeRelationshipTracker = employeeRelationship::where('assesseeEmployeeID',$targetEmployeesItem->id)
                                        ->where('is_active',1)
                                        ->whereNull('deleted_at')
                                        ->get();
                                    
                                        //return $employeeRelationshipTracker;
                                    if($employeeRelationshipTracker){
                                        $totalBothCount = 0;
                                        foreach($employeeRelationshipTracker as $employeeRelationshipTrackerItem){
                                            foreach($gapAnalysisSettingAssessmentType as $gapAnalysisSettingAssessmentTypeItem){
                                                if ($gapAnalysisSettingAssessmentTypeItem->assessmentTypeID == $employeeRelationshipTrackerItem->relationshipID ) {
                                                    $assessmentTracker = assessment::where('employeeID',$employeeRelationshipTrackerItem->assesseeEmployeeID)->where('assessmentTypeID',$gapAnalysisSettingAssessmentTypeItem->assessmentTypeID)->whereNull('deleted_at')->first();
                                                    //echo $assessmentTracker;
                                                    if($assessmentTracker){
                                                        $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                                        if($evaluationTracker){
                                                            $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                            if($evaluationCompetency){
                                                                $model_evaluation_counter = 0;
                                                                foreach ($model as $modelItem) {
                                                                    $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                                    if ($evaluationChecker) {
                                                                        $model_evaluation_counter++;
                                                                    }
                                                                }
                                                                if($model_evaluation_counter == $countModel){
                                                                    $totalBothCount++;
                                                                }
                                                            }
                                                        } 
                                                    }
                                                    if($totalBothCount == $countGapAnalysisSettingAssessmentType){
                                                        $subtotalAssessmentBothCount++;
                                                        
                                                    } 
                                                }
                                            }
                                            
                                            
                                        }
                                        
                                    }
                                }
                                $arrayRowBoth = new stdClass();
                                $arrayRowBoth->roleName = $roleItem->name;
                                $arrayRowBoth->assessmentType = 'Both Assessment';
                                $arrayRowBoth->target = count($targetEmployees);
                                if ($subtotalAssessmentBothCount == 0) {
                                    $arrayRowBoth->assessed = 'N/A';
                                }else {
                                    $arrayRowBoth->assessed = $subtotalAssessmentBothCount;
                                }
                                
                                //return $subtotalAssessmentBothCount;
                                $arrayRowBoth->completion = 'N/A';
                                if ($subtotalAssessmentBothCount > 0 && count($targetEmployees) > 0 ) {
                                    $arrayRowBoth->completion = ($subtotalAssessmentBothCount / count($targetEmployees)) * 100;
                                } 
                                array_push($arraySelection,$arrayRowBoth);
                            }
                        }
                    }
                }
            }
        }

        $export = new completionTrackerSummaryExport($arraySelection);
        return Excel::download($export, 'CompletionTracker.xlsx');
    }


    public function breakdown(Request $request,$id) 
    {
        
        $completionTrackerAssignment = completionTrackerAssignment::find($id);
        
        $groupsPerGapAnalysisSettingRole = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->get();

        $groupsPerGapAnalysisSettingRoleModel = groupsPerGapAnalysisSettingRole::where('gpgas_id_foreign',$completionTrackerAssignment->gpgas_id_foreign)->whereNull('deleted_at')->first();
        
        $modelGroup = listOfCompetenciesPerRole::where('roleID',$groupsPerGapAnalysisSettingRoleModel->roleID)->first();
        $group = group::find($modelGroup->groupID);

        $roles_selected_id = null;
        $roles_selected = array();
        foreach($groupsPerGapAnalysisSettingRole as $modelBasisItem){
            array_push($roles_selected,strval($modelBasisItem->roleID));
        }
        foreach($groupsPerGapAnalysisSettingRole as $item){
            $roles_selected_id .= $item->roleID.',';
        }

        $role = role::whereNull('deleted_at')->get();
        $assessmentTypeTracker = assessmentType::whereNull('deleted_at')->get();
        $competencyTypeTracker = competencyType::whereNull('deleted_at')->get();
        $employeeTracker = employee_data::whereNull('deleted_at')->get();
        $employeeRelationshipTracker = employeeRelationship::whereNull('deleted_at')->get();
        $relationshipTracker = relationship::whereNull('deleted_at')->get();
        
        //return $roles_selected;
        $arrayBreakdown = array();
        
        if($roles_selected){
            foreach($roles_selected as $rsItem){
                foreach($role as $roleItem){
                    if($rsItem == $roleItem->id){
                        if($assessmentTypeTracker){
                            foreach($assessmentTypeTracker as $assessmentTypeItem){

                                $targetEmployees = employee_data::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();

                                $selectedAssessmentType = assessmentType::where('relationshipID', $assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                $countModel = count($model);

                                for($i=0;$i<count($targetEmployees);$i++){
                                    $employeeRelationshipTracker = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                        ->where('assesseeEmployeeID',$targetEmployees[$i]->id)
                                        ->where('relationshipID',$selectedAssessmentType->relationshipID)
                                        ->where('is_active',1)
                                        ->whereNull('deleted_at')
                                        ->first();

                                    if ($employeeRelationshipTracker) {
                                        $arrayRow = new stdClass();
                                        $arrayRow->roleName = $roleItem->name;
                                        $arrayRow->assessmentType = $assessmentTypeItem->name;
                                        
                                        $assessee = employee_data::where('id',$employeeRelationshipTracker->assesseeEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assesseeName = $assessee->firstname . ' ' . $assessee->lastname;
    
                                        $assessor = employee_data::where('id',$employeeRelationshipTracker->assessorEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assessorName = $assessor->firstname . ' ' . $assessor->lastname;
                                        $arrayRow->completion = 'Pending'; 

                                        $assessmentTracker = assessment::where('employeeID',$assessee->id)->where('assessmentTypeID',$assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                        if($assessmentTracker){
                                            $evaluationTracker = evaluation::where('id',$assessmentTracker->evaluationVersionID)->whereNull('deleted_at')->first();
                                            if($evaluationTracker){
                                                $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->get();
                                                if($evaluationCompetency){
                                                    $model_evaluation_counter = 0;
                                                    foreach ($model as $modelItem) {
                                                        $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluationTracker->id)->whereNull('deleted_at')->first();
                                                        if ($evaluationChecker) {
                                                            $model_evaluation_counter++;
                                                        }
                                                    }
                                                    if($model_evaluation_counter == $countModel){
                                                        $arrayRow->completion = 'Completed'; 
                                                    }elseif($model_evaluation_counter <= $countModel && $model_evaluation_counter >0){
                                                        $arrayRow->completion = 'On-Going'; 
                                                    }
                                                }
                                            } 
                                        }
                                        array_push($arrayBreakdown,$arrayRow);
                                    }
                                    
                                }
                                
                            }
                        }
                    }
                }
            }
        }

        /*
        if($roles_selected){
            foreach($roles_selected as $rsItem){
                foreach($role as $roleItem){
                    if($rsItem == $roleItem->id){
                        if($assessmentType){
                            foreach($assessmentType as $assessmentTypeItem){
                                $targetEmployees = employee_data::where('groupID',$groupID)->where('roleID',$roleItem->id)->whereNull('deleted_at')->get();

                                $selectedAssessmentType = assessmentType::where('relationshipID', $assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                $model = listOfCompetenciesPerRole::where('roleID',$roleItem->id)->whereNull('deleted_at')->get();
                                $countModel = count($model);

                                for($i=0;$i<count($targetEmployees);$i++){
                                    $employeeRelationship = employeeRelationship::where('relationshipID',$assessmentTypeItem->relationshipID)
                                        ->where('assesseeEmployeeID',$targetEmployees[$i]->employeeID)
                                        ->where('relationshipID',$selectedAssessmentType->relationshipID)
                                        ->where('is_active',1)
                                        ->whereNull('deleted_at')
                                        ->first();

                                    if ($employeeRelationship) {
                                        $arrayRow = new stdClass();
                                        $arrayRow->roleName = $roleItem->name;
                                        $arrayRow->assessmentType = $assessmentTypeItem->name;
                                        
                                        $assessee = employee_data::where('id',$employeeRelationship->assesseeEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assesseeName = $assessee->employeeID . ' - ' . $assessee->firstname . ' ' . $assessee->lastname;
    
                                        $assessor = employee_data::where('id',$employeeRelationship->assessorEmployeeID)->whereNull('deleted_at')->first();
    
                                        $arrayRow->assessorName = $assessor->employeeID . ' - ' . $assessor->firstname . ' ' . $assessor->lastname;
                                        $arrayRow->completion = 'On-Going'; 

                                        $assessment = assessment::where('employeeID',$assessee->employeeID)->where('assessmentTypeID',$assessmentTypeItem->id)->whereNull('deleted_at')->first();
                                        if($assessment){
                                            $evaluation = evaluation::where('id',$assessment->evaluationVersionID)->whereNull('deleted_at')->first();
                                            if($evaluation){
                                                $evaluationCompetency = evaluationCompetency::where('evaluationID',$evaluation->id)->whereNull('deleted_at')->get();
                                                if($evaluationCompetency){
                                                    $model_evaluation_counter = 0;
                                                    foreach ($model as $modelItem) {
                                                        $evaluationChecker = evaluationCompetency::where('competencyID',$modelItem->competencyID)->where('evaluationID',$evaluation->id)->whereNull('deleted_at')->first();
                                                        if ($evaluationChecker) {
                                                            $model_evaluation_counter++;
                                                        }
                                                    }
                                                    if($model_evaluation_counter == $countModel){
                                                        $arrayRow->completion = 'Completed'; 
                                                    }else{
                                                        $arrayRow->completion = 'On-Going'; 
                                                    }
                                                }
                                            } 
                                        }
                                        array_push($arrayBreakdown,$arrayRow);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }*/
        //return $arrayBreakdown;
        $export = new completionTrackerBreakdownExport($arrayBreakdown);
        return Excel::download($export, 'CompletionTrackerBreakdown.xlsx');
    }

    public function myProfileView()
    {
        //Required Variables
        $employeeInfo = employee_data::find(auth()->user()->employeeID);
        $employeeRelationship = employeeRelationship::select('assessorEmployeeID','relationshipID')->where('assessorEmployeeID',$employeeInfo->id)->where('is_active',1)->whereNull('deleted_at')->distinct()->get();
        $relationship = relationship::whereNull('deleted_at')->get();
        $assessmentType = assessmentType::whereNull('deleted_at')->get();

        $supervisor = employee_data::where('employeeID',$employeeInfo->supervisorID)->whereNull('deleted_at')->first();
        $group = group::whereNull('deleted_at')->get();
        $division = division::whereNull('deleted_at')->get();
        $department = department::whereNull('deleted_at')->get();
        $section = section::whereNull('deleted_at')->get();
        $role = role::whereNull('deleted_at')->get();
        $job = job::whereNull('deleted_at')->get();
        $band = band::whereNull('deleted_at')->get();

        return view('user.account.profile')->with(compact('employeeRelationship','relationship','assessmentType','employeeInfo',
            'group','division','department','section','role','job','band','supervisor'
        ));
    }

    public function myProfileEdit()
    {
        
    }
}