@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-jason-blue " style="min-height:35vh;">
            <div class="row pt-4">
                <div class="col-12">
                    <ul class="breadcrumbs">
                        <li class="nav-home">
                            <a href="{{route('home')}}" class="">
                                <i class="flaticon-home"></i>
                            </a>
                        </li>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="">My Assessment</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="page-inner pt-2">
                <div class="row">
                    <div class="col-sm-12 pt-3 pb-2">
                        <h1 id="hello-user" class="text-center ">Hello, {{ $user->firstname }}.</h1>
                        @if ($employeeRelationship)
                            <h3 id="ready-user" class="text-center ">
                                @if (count($employeeRelationship) == 1)
                                There is only {{ count($employeeRelationship)}}  assessment type
                                @elseif(count($employeeRelationship) > 1)
                                There are {{ count($employeeRelationship)}} assessment types
                                @else
                                    
                                @endif
                                assigned to you. </h3>
                        @else
                            
                            <h3 id="ready-user" class="text-center ">There are no assessments assigned to you. </h3>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container mt--4">
            <div id="load-content" class="row">
                <div class="col-sm-12">
                    @if ($employeeRelationship)
                        <div class="accordion accordion-secondary">
                            @if ($assessmentType)
                                @foreach ($employeeRelationship as $employeeRelationshipItem)
                                    @foreach ($assessmentType as $assessmentTypeItem)    
                                        @if ($employeeRelationshipItem->relationshipID == $assessmentTypeItem->relationshipID)
                                            <div class="card" style="-webkit-box-shadow: 1px 5px 7px 0px rgba(0,0,0,0.14);
                                            -moz-box-shadow: 1px 5px 7px 0px rgba(0,0,0,0.14);
                                            box-shadow: 1px 5px 7px 0px rgba(0,0,0,0.14);">
                                                <div class="card-header collapsed" title="Click to view the assessment." id="heading{{$assessmentTypeItem->id}}" data-toggle="collapse" data-target="#collapse{{$assessmentTypeItem->id}}" aria-expanded="false" aria-controls="collapse{{$assessmentTypeItem->id}}">
                                                    <div class="avatar avatar-md mr-4">
                                                        <img src="{{asset('assessment-images/feedback 1.svg')}}" alt="..." class="avatar-img">
                                                    </div>
                                                    <h4 class="text-dark text-uppercase font-weight-bold">{{$assessmentTypeItem->name}}</h4>
                                                        
                                                    <div class="span-mode text-dark"></div>
                                                </div>
                                                <div id="collapse{{$assessmentTypeItem->id}}" class="collapse" aria-labelledby="heading{{$assessmentTypeItem->id}}" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <p>
                                                            {{$assessmentTypeItem->definition}}
                                                        </p>
                                                        @if ($employeeRelationshipList)
                                                            @if ($role)
                                                                <div class="table-responsive">
                                                                    <table id="employee-datatables-{{$assessmentTypeItem->id}}" class="display table table-striped table-hover" cellspacing="0" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Employee ID</th>
                                                                                <th>First Name</th>
                                                                                <th>Last Name</th>
                                                                                <th>Email</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($employeeRelationshipList as $employeeRelationshipListItem)
                                                                                @foreach ($employee as $employeeItem)
                                                                                        @if ($employeeRelationshipListItem->assesseeEmployeeID == $employeeItem->id &&
                                                                                            $employeeRelationshipListItem->relationshipID ==  $employeeRelationshipItem->relationshipID) 
                                                                                            @if ($employeeRelationshipListItem->assesseeEmployeeID != $employeeInfo->id)
                                                                                                <tr>
                                                                                                    <td>{{$employeeItem->employeeID}}</td>
                                                                                                    <td>{{$employeeItem->firstname}}</td>
                                                                                                    <td>{{$employeeItem->lastname}}</td>
                                                                                                    <td><a href="my-assessment/{{$assessmentTypeItem->id}}/{{$employeeItem->id}}/start" class="btn btn-success">ASSESS</a></td>
                                                                                                </tr>    
                                                                                            @else
                                                                                                <tr>
                                                                                                    <td class="text-uppercase text-center" colspan="3">You are Assessing yourself</td>
                                                                                                    <td><a href="my-assessment/{{$assessmentTypeItem->id}}/{{$employeeItem->id}}/start" class="btn btn-success">ASSESS</a></td>
                                                                                                </tr>    
                                                                                            @endif
                                                                                        @endif
                                                                                @endforeach
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <script>
                                                                    $(document).ready(function() {
                                                                            $('#employee-datatables-{{$assessmentTypeItem->id}}').DataTable();
                                                                    });
                                                                </script>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                    @else
                        
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-uppercase font-weight-bold">No Assessment</h3>
                                <p>Please come back at another time.</p> 

                                <hr>
                                <p>If you think this is a mistake, please contact our administrator or give us an e-mail at globeuniversity@globe.com.ph</p>
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
        $('#hello-user').hide(0).delay(500).fadeIn(1000);
        $('#ready-user').hide(0).delay(2000).fadeIn(1000);
        $('#load-content').hide(0).delay(3500).fadeIn(1000);
    });
    </script>
    

    @if ($assessmentType)
        @foreach ($employeeRelationship as $employeeRelationshipItem)
            @foreach ($assessmentType as $assessmentTypeItem)    
                @if ($employeeRelationshipItem->relationshipID == $assessmentTypeItem->relationshipID)
                    @if ($employeeRelationshipList)
                        <script>
                            $(document).ready(function() {
                                    $('#employee-datatables-{{$assessmentTypeItem->id}}').DataTable();
                            });
                        </script>
                    @endif
                @endif
            @endforeach
        @endforeach
    @endif

@endsection