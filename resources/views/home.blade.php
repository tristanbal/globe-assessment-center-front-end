@extends('layouts.app')

@section('content')
<div  class="main-panel">
    <div id="start-up" class="content hidden">
        <div class="panel-header ">
            <div class="page-inner pt-5">
                <div  class="d-flex align-items-left align-items-md-center flex-column flex-md-row ">
                    <div>
                        <h2 class=" pb-1 fw-bold">Welcome to the Assessment Center Portal, {{ $user->firstname}}.</h2>
                        <h5 class=" op-2 mb-1">To begin with your assessment, click <a href="{{route('assessments.index')}}">'Start My Assessment'</a>.</h5>
                    </div>
                    <div class="ml-md-auto  py-md-0">
                        <a title="Click to start assessment." href="{{route('assessments.index')}}" class="btn btn-primary btn-round">Start My Assessment</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div  class="page-inner mt--3 ">
            <div class="row mt--1 ">
                <div class="col-md-6">
                    @if (count($completionTrackerAssignment)>0)
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-primary bg-primary-gradient">
                                    <div class="card-body">
                                        <h2 class=" b-b1 pb-2 mb-4 fw-bold">COMPLETION TRACKER</h2>
                                        <h6 class="mt-3 pb-2">You were given access by the administrator to view a completion tracker. Click here to see the whole tracker.</h6>
                                        @foreach ($completionTrackerAssignment as $item)
                                            <div class="d-flex">
                                                <div class="avatar">
                                                    <img src="{{asset('atlantis/assets/img/logoproduct.svg')}}" alt="..." class="avatar-img rounded-circle">
                                                </div>
                                                <div class="flex-1 pt-1 ml-2">
                                                    <h6 class="fw-bold mb-1">{{$item->gpgas->name}}</h6>
                                                    <small style="color:white !important;" class="text-white text-muted">Click 'VIEW' to see the complete tracker.</small>
                                                </div>
                                                <div class="d-flex ml-auto align-items-center">
                                                    <a href="{{route('completion-tracker.index', ['id' => $item->id])}}" class="btn btn-primary">VIEW</a>
                                                </div>
                                            </div>
                                            <div class="separator-dashed"></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                            <div class="avatar avatar-xxl">
                                                <img src="{{asset('assessment-images/feedback 3.svg')}}" alt="..." class="avatar-img ">
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="card-title text-uppercase font-weight-bold">What is Competency Assessment?</div>
                                            <div class="card-category">This online assessment tool uses a 180 - degree feedback approach, which makes use of your self-assessment and your immediate supervisor's assessment. The output of the tool is a report that will identify your strengths and developmental needs, and can be used in formulating your Individual Development Plan.</div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="card-title">Progress</div>
                                    <div class="card-category">Version 2.1.6 - 2019 Release</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @if ($employeeRelationship)
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title text-uppercase font-weight-bold">Your Assigned Assessments</div>
                            </div>
                            <div class="card-body pb-0">
                                @foreach ($employeeRelationship as $employeeRelationshipItem)
                                    @foreach ($assessmentType as $assessmentTypeItem)    
                                        @if ($employeeRelationshipItem->relationshipID == $assessmentTypeItem->relationshipID)
                                            <div class="d-flex">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="avatar avatar-xl px-2">
                                                        <img src="{{asset('assessment-images/feedback 1.svg')}}" alt="..." class="avatar-img">
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-1 pt-1 ml-2">
                                                    <h6 class="fw-bold mb-1">{{$assessmentTypeItem->name}}</h6>
                                                    <small class="text-muted">{{$assessmentTypeItem->definition}}</small>
                                                </div>
                                            </div>
                                            <div class="separator-dashed"></div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        
                    @else
                        <div class="card ">
                            <div class="card-body">
                                <div class="card-title">No Assessment found.</div>
                                <div class="card-category">There are no assigned assessment for you.</div> 
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>
            
            
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('#start-up').fadeIn(1000).removeClass('hidden');
        $('#start-more').delay(2000).fadeIn(1000).removeClass('hidden');
    });
    
</script>

@endsection