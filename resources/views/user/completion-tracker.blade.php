@extends('layouts.app')

@section('content')
<div  class="main-panel">
    <div id="start-up" class="content">
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
                        <a href="#" class="">Completion Tracker</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container pt-4">
            <h1 class="text-uppercase"><b>{{$completionTrackerAssignment->gpgas->name}}</b> - {{$group->name}} </h1>
            

            <h1 class="text-uppercase "></h1> 
    
            <div class="row">
                <div class="col-sm-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">Overall Details</div>
                            <div class="card-category">Target Employees: <b>{{$finalTargetTotal}}</b><br>
                                Compeleted Assessment: <b>{{$finalAssessedTotal}}</b>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                    <div class="px-2 pb-2 pb-md-0 text-center">
                                            <div id="circles-1"></div>
                                            
                                        </div>
                                        <h1 class="fw-bold mt-3 mb-0">Completion Rate</h1>
                                <!--<h1><b>{{ number_format((float)$finalPercentageTotal, 2, '.', '') }}%</b> Completion Rate</h1>-->
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="card-title">Roles</div>
                            <div class="card-category">
                                @if ($roles_name)
                                    @foreach ($roles_name as $item)
                                    <?php
                                        $words = preg_split("/[\s,_-]+/", $item);
                                        $acronym = "";

                                        foreach ($words as $w) {
                                            for($i=0;$i<strlen($w);$i++){
                                                if(ctype_upper($w[$i])){
                                                $acronym .= $w[$i];
                                                }
                                            } 
                                        }
                                    ?>
                                        <b><i>({{$acronym}}) </i></b>{{$item}}<br>
                                    @endforeach
                                @endif
                                
                            </div>
                            
                        </div>
                        <div class="card-header">
                            <div class="card-title">Applied Computation</div>
                            <div class="card-category">
                                @if ($gapAnalysisSettingAssessmentType)
                                    @foreach ($gapAnalysisSettingAssessmentType as $item)
                                        {{$item->assessmentType->name}} - {{$item->percentAssigned}}%<br>
                                    @endforeach
                                @endif
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <ul class="nav nav-pills nav-secondary  nav-pills-no-bd nav-pills-icons justify-content-center mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab-icon" data-toggle="pill" href="#pills-home-icon" role="tab" aria-controls="pills-home-icon" aria-selected="true">
                            <i class="fas fa-certificate"></i>
                            Summary
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab-icon" data-toggle="pill" href="#pills-profile-icon" role="tab" aria-controls="pills-profile-icon" aria-selected="false">
                            <i class="fas fa-chart-bar"></i>
                            Breakdown
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-3" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home-icon" role="tabpanel" aria-labelledby="pills-home-tab-icon">
                            @if ($roles_selected)
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h1 class="text-center">Summary of Roles Completion</h1>
                                        <p class="text-center">This module tracks the completion rate of specific roles per assessment type found within the assessment portal.</p>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="multipleBarChart"></canvas>
                                            </div>
                                        </div>
                                        <form action="{{route('completionTrackers.export', ['id' => $id])}}" method="GET">
                                            <input type="text" value="{{$roles_selected_id}}" name="roles_selected_id" hidden>
                                            <button class="btn btn-success text-center" type="submit">Export Summary</button>
                                        </form>
                                    </div>
                                </div>
                                
                                @foreach ($roles_selected as $rsItem)
                                    @foreach ($role as $roleItem)
                                        @if ($rsItem == $roleItem->id)
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="text-center">{{$roleItem->name}}</h3>
                                                    <div class="table-responsive pb-4">
                                                        <table id="role-summary-{{$roleItem->id}}" class="display table table-striped table-hover" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Assessment Type</th>
                                                                    <th>Target</th>
                                                                    <th>Assessed</th>
                                                                    <th>Completion Rate</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($arraySelection)
                                                                    @foreach ($arraySelection as $arraySelectionItem)
                                                                        @if ($arraySelectionItem->roleID == $roleItem->id )
                                                                            <tr>
                                                                                <td>{{$arraySelectionItem->assessmentType}}</td>
                                                                                <td>{{$arraySelectionItem->target}}</td>
                                                                                <td>{{$arraySelectionItem->assessed}}</td>
                                                                                <td>{{number_format((float)$arraySelectionItem->completion, 2, '.', '')}}%</td>
                                                                            </tr>
                                                                            
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else 
                                <p>No roles were selected.</p>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="pills-profile-icon" role="tabpanel" aria-labelledby="pills-profile-tab-icon">
                            @if ($roles_selected)

                                <div class="card">
                                    <div class="card-body text-center">
                                        <h1 class="text-center">Breakdown of Roles Completion</h1>
                                        <p class="text-center">This module tracks the completion status of specific assessment per employee in a specific role cluster per assessment type.</p>
                                        <form action="{{route('completionTrackers.export.breakdown', ['id' => $id])}}" method="GET">
                                            <input type="text" value="{{$roles_selected_id}}" name="roles_selected_id" hidden>
                                            <button class="btn btn-success text-center" type="submit">Export Breakdown</button>
                                        </form>
                                    </div>
                                </div>
                                @foreach ($roles_selected as $rsItem)
                                    @foreach ($role as $roleItem)
                                        @if ($rsItem == $roleItem->id)
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="text-center">{{$roleItem->name}}</h3>
                                                    <div class="table-responsive pb-4">
                                                        <table id="role-breakdown-{{$roleItem->id}}" class="display table table-striped table-hover" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Assessment Type</th>
                                                                    <th>Assessor</th>
                                                                    <th>Assesseee</th>
                                                                    <th>Completion Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($arrayBreakdown)
                                                                    @foreach ($arrayBreakdown as $arrayBreakdownItem)
                                                                        @if ($arrayBreakdownItem->roleID == $roleItem->id )
                                                                            <tr>
                                                                                <td>{{$arrayBreakdownItem->assessmentType}}</td>
                                                                                <td>{{$arrayBreakdownItem->assessorEmployeeID}} | {{$arrayBreakdownItem->assessorName}}</td>
                                                                                <td>{{$arrayBreakdownItem->assesseeEmployeeID}} | {{$arrayBreakdownItem->assesseeName}}</td>
                                                                                <td class=" text-white 
                                                                                @if ($arrayBreakdownItem->completion  == 2)
                                                                                    bg-warning
                                                                                @elseif($arrayBreakdownItem->completion == 0)
                                                                                    bg-primary
                                                                                @else
                                                                                    bg-success
                                                                                @endif
                                                                                
                                                                                ">
                                                                                @if ($arrayBreakdownItem->completion  == 2)
                                                                                    Incomplete Assessment
                                                                                @elseif($arrayBreakdownItem->completion == 0)
                                                                                    Pending Assessment
                                                                                @else
                                                                                    Completed
                                                                                @endif
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else 
                                <p>No roles were selected.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">    
                    
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
@section('scripts')

    <script>
        var multipleBarChart = document.getElementById('multipleBarChart').getContext('2d');
        var myMultipleBarChart = new Chart(multipleBarChart, {
			type: 'bar',
			data: {
				labels: [
                    @if(count($roles_name)>3)
                        @foreach ($roles_name as $item)
                            @if(strlen($item) <= 15)
                            '{{$item}}',
                            @else
                            <?php
                                $words = preg_split("/[\s,_-]+/", $item);
                                $acronym = "";

                                foreach ($words as $w) {
                                    for($i=0;$i<strlen($w);$i++){
                                        if(ctype_upper($w[$i])){
                                        $acronym .= $w[$i];
                                        }
                                    } 
                                }
                            ?>
                            '{{$acronym}}',
                            @endif
                        @endforeach
                    @else
                        @foreach ($roles_name as $item)
                            @if(strlen($item) <= 35)
                            '{{$item}}',
                            @else
                            <?php
                                $words = preg_split("/[\s,_-]+/", $item);
                                $acronym = "";

                                foreach ($words as $w) {
                                    for($i=0;$i<strlen($w);$i++){
                                        if(ctype_upper($w[$i])){
                                        $acronym .= $w[$i];
                                        }
                                    } 
                                }
                            ?>
                            '{{$acronym}}',
                            @endif
                        @endforeach
                    @endif

                    ],
				datasets : [{
					label: "Completed",
					backgroundColor: '#59d05d',
					borderColor: '#59d05d',
					data: [
                        @foreach($arraySelection as $item)
                            @if($item->assessmentTypeID == 0)
                            '{{$item->assessed}}',
                            @endif
                        @endforeach
                        ],
				},{
					label: "Incomplete Assessment",
					backgroundColor: '#E85860',
					borderColor: '#E85860',
					data: [
                        @foreach($arraySelection as $item)
                            @if($item->assessmentTypeID == 0)
                            '{{ $item->target-$item->assessed}}',
                            @endif
                        @endforeach
                    ],
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				legend: {
					position : 'bottom'
				},
				title: {
					display: true,
					text: 'Overall Count and Completion Status Per Role'
				},
				tooltips: {
					mode: 'index',
					intersect: false
				},
				responsive: true,
				scales: {
					xAxes: [{
						stacked: true,
					}],
					yAxes: [{
						stacked: true
					}]
				}
			}
		});
        Circles.create({
                id:'circles-1',
                radius:60,
                value: {!! json_encode($finalPercentageTotal, JSON_HEX_TAG) !!},
                maxValue:100,
                width:10,
                //text: {!! json_encode($finalPercentageTotal, JSON_HEX_TAG) !!},
                text: "{{number_format((float)$finalPercentageTotal, 0, '', '')}}%",
                colors:['#f1f1f1', '#2BB930'],
                duration:400,
                wrpClass:'circles-wrp',
                textClass:'circles-text',
                styleWrapper:true,
                styleText:true
            })
    </script>
    @if ($roles_selected)
        @foreach ($roles_selected as $rsItem)
            @foreach ($role as $roleItem)
                @if ($rsItem == $roleItem->id)
                    <script>
                        $(document).ready(function() {
                            $('#role-summary-{{$roleItem->id}}').DataTable({
                                dom: 'Bfrtip',
                                    buttons: [
                                        'copy', 'csv', 'excel', 'pdf', 'print'
                                    ]
                            });
                        });
                    </script>
                
                @endif
                
            @endforeach
        @endforeach
    @endif

    @if ($roles_selected)
        @foreach ($roles_selected as $rsItem)
            @foreach ($role as $roleItem)
                @if ($rsItem == $roleItem->id)

                    <script>
                        $(document).ready(function() {
                            $('#role-breakdown-{{$roleItem->id}}').DataTable({
                                dom: 'Bfrtip',
                                    buttons: [
                                        'copy', 'csv', 'excel', 'pdf', 'print'
                                    ]
                            });
                        });
                    </script>

                    
                @endif
            @endforeach
        @endforeach
    @endif
@endsection