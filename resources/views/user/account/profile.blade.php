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
                        <a href="#" class="">My Profile</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container pt-4">
            <h1 class="text-uppercase">My Profile</h1>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body"> 

                            <div class="row">
                                <div class="col-sm-3 ">
                                        <img src="@if(auth()->user()->profileImage == 'no-image.png')
                                        {{asset('stock/'.auth()->user()->profileImage)}}
                                        @else
                                            {{auth()->user()->profileImage}}
                                        @endif" alt="..." class="" style="width:100%;height:auto;">
                                </div>
                                <div class="col-sm-9">
                                    <div class="py-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class=""><span class="font-weight-light">Full Name:</span> <b>{{$employeeInfo->firstname}} {{$employeeInfo->middlename}} {{$employeeInfo->lastname}}</b></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h3 class=""><span class="font-weight-light">Employee ID:</span>  <b>{{$employeeInfo->employeeID}} </b></h3>
                                            </div>
                                            <div class="col-6">
                                                <h3 class=""><span class="font-weight-light">Email:</span> <b>{{$employeeInfo->email}}</b></h3>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h3 class=""><span class="font-weight-light">Phone Number:</span> <b>{{$employeeInfo->phone}}</b></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class=""><span class="font-weight-light">Supervisor:</span> <b>{{$supervisor->employeeID}} - {{$supervisor->firstname}} {{$supervisor->middlename}} {{$supervisor->lastname}}</b></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                @if ($role)
                                                    @foreach ($role as $roleItem)
                                                        @if ($roleItem->id == $employeeInfo->roleID)
                                                        <h3 class=""><span class="font-weight-light">Role:</span> <b>{{$roleItem->name}}</b></h3>
                                                        @endif        
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                @if ($band)
                                                    @foreach ($band as $bandItem)
                                                        @if ($bandItem->id == $employeeInfo->bandID)
                                                        <h3 class=""><span class="font-weight-light">Band:</span> <b>{{$bandItem->name}}</b></h3>
                                                        @endif        
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                @if ($group)
                                                    @foreach ($group as $groupItem)
                                                        @if ($groupItem->id == $employeeInfo->groupID)
                                                            <h3 class=""><span class="font-weight-light">Group:</span><br> 
                                                                <b>{{$groupItem->name}}</b></h3>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                @if ($division)
                                                    @foreach ($division as $divisionItem)
                                                        @if ($divisionItem->id == $employeeInfo->divisionID)
                                                            <h3 class=""><span class="font-weight-light">Division:</span><br> 
                                                                <b>{{$divisionItem->name}}</b></h3>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                @if ($department)
                                                    @foreach ($department as $departmentItem)
                                                        @if ($departmentItem->id == $employeeInfo->departmentID)
                                                            <h3 class=""><span class="font-weight-light">Department:</span><br> 
                                                                <b>{{$departmentItem->name}}</b></h3>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                @if ($section)
                                                    @foreach ($section as $sectionItem)
                                                        @if ($sectionItem->id == $employeeInfo->sectionID)
                                                            <h3 class=""><span class="font-weight-light">Section:</span><br> 
                                                                <b>{{$sectionItem->name}}</b></h3>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>
@endsection
@section('scripts')

<script>
    
</script>

@endsection