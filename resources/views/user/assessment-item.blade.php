@extends('layouts.app')

@section('content')
<style>
    
</style>
<div class="main-panel">
    <div id="hello-user"  class="content">
        <div  class="row pt-4 container-fluid">
            <div class="col-12">
                <ul class="breadcrumbs ">
                    <li class="nav-home">
                        <a href="{{route('home')}}">
                            <i class="flaticon-home "></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item ">
                        <a href="{{route('assessments.index')}}">My Assessment</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" >{{$assessmentTypeUser->name}}</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" >{{$assesseeEmployeeID->employeeID}} - {{$assesseeEmployeeID->firstname}} {{$assesseeEmployeeID->lastname}}</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" >{{$competencyItem->name}}</a>
                    </li>
                </ul>
            </div>
        </div>
         
        <div class="container-fluid" style="width:95%"> 
            @include('inc.messages')
            <div class="row">
                <div class="col-12 pt-3">
                    <h1>{{$competencyItem->name}}</h1>
                    <h5><i>{{$competencyItemType->name}}</i></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p> {!! nl2br($competencyItem->definition) !!}</p>
                </div>
            </div>
            
            <div class=" "  id = "{{$modelItem->id}}competencyItemSectionProficiencies">
                
                @if ($proficiencyItem)
                    @if (count($proficiencyItem)<= 6)
                        <div class="row pb-4">
                            <div class="col-12">
                                <div class="card-deck">
                                    <div class="competencyItemSection" id = "{{$modelItem->id}}competencyItemSection">
                                        <form action="{{ route('assessments.user.store') }}" method="POST" >
                                            @csrf
                                            <div class="row competencyItemSectionProficiencies " id = "{{$modelItem->id}}competencyItemSectionProficiencies">
                                            
                                            @foreach ($proficiencyItem as $item)
                                                <button id="" title="To select this proficiency, kindly click the card." data-value="{{$item->dataValue}}" class="proficiencyItemCard card gu-assessment-card proficiencyCard w-100
                                                    @if($userStoredEvaluationCompetency)
                                                    @if($userStoredEvaluationCompetency->givenLevelID == $item->dataValue)
                                                        selected
                                                    @endif
                                                    @endif
                                                    " type="submit">
                                                    <div class="w-100 " style="padding:8px;">
                                                        <h5 class="text-center "><B>{{$item->levelName}}</B></h5>
                                                        <hr>
                                                        <p class="card-text ">{!! nl2br($item->definition) !!}</p>
                                                        <br>
                                                    </div> 
                                                </button>
                                            @endforeach
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                    @endif
                @endif
            </div>

            <input type="text" value = "{{$assessmentTypeUser->id}}" id="assessmentTypeID" name="assessmentTypeID" hidden/>
            <input type="text" value = "{{$assessorEmployeeID->id}}" id="assessorEmployeeID" name="assessorEmployeeID" hidden/>
            <input type="text" value = "{{$assesseeEmployeeID->id}}" id="assesseeEmployeeID" name="assesseeEmployeeID" hidden/>
            <input type="text" value = "{{$assessorRole->id}}" id="assessorRoleID" name="assessorRoleID"  hidden/>
            <input type="text" value = "{{$assesseeEmployeeID->roleID}}" id="assesseeRoleID" name="assesseeRoleID"  hidden/>
            <input type="text" value = "{{$modelItem->id}}" id="modelCompetencyID" name="modelCompetencyID" hidden/>
            <input type="text" value = "{{$modelItem->competencyID}}" id="competencyID" name="competencyID" hidden/>
            <input type="text" value = "{{$modelItem->competencyTypeID}}" id="competencyTypeID" name="competencyTypeID" hidden/>
            <input type="text" value = "{{$modelItem->targetLevelID}}" id="targetLevelID" name="targetLevelID" hidden/>
            

            <div class="row pb-3">
                <div class="col-sm-4">
                    <!--
                    <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" title="A verbatim response on the given competency." id="v-pills-home-tab-nobd" data-toggle="pill" href="#v-pills-home-nobd" role="tab" aria-controls="v-pills-home-nobd" aria-selected="true">Verbatim Assessment</a>
                        <a class="nav-link" title="Additional respones which require files to be uploaded." id="v-pills-profile-tab-nobd" data-toggle="pill" href="#v-pills-profile-nobd" role="tab" aria-controls="v-pills-profile-nobd" aria-selected="false">Attachment</a>
                    </div>-->
                    <!-- Button trigger modal -->
                    <div class="py-1">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-border btn-block btn-round" data-toggle="modal" data-target="#exampleModal">
                            Verbatim Assessment
                        </button>
                    </div>
  
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verbatim Assessment (Optional)</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <form action="{{ route('assessments.user.verbatim',['type' => $assessmentTypeUser->id, 'employeeID' => $assesseeEmployeeID->id, 'modelID' => $modelItem->id]) }}" method="POST" >
                                        @csrf
                                        <p for="email2">Please provide examples of how the competency is being applied. <i>*Optional</i></p>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Verbatim</span>
                                            </div>
                                            <textarea name="verbatim" class="form-control" aria-label="With textarea">@if($userStoredEvaluationCompetency){{$userStoredEvaluationCompetency->verbatim}}@endif
                                            </textarea>
                                        </div><br>
                                        
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                            </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <!--
                    <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" title="A verbatim response on the given competency." id="v-pills-home-tab-nobd" data-toggle="pill" href="#v-pills-home-nobd" role="tab" aria-controls="v-pills-home-nobd" aria-selected="true">Verbatim Assessment</a>
                        <a class="nav-link" title="Additional respones which require files to be uploaded." id="v-pills-profile-tab-nobd" data-toggle="pill" href="#v-pills-profile-nobd" role="tab" aria-controls="v-pills-profile-nobd" aria-selected="false">Attachment</a>
                    </div>-->
                    <div class="py-1">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-border btn-block btn-round" data-toggle="modal" data-target="#attachmentModal">
                            Attachment
                        </button>
                    </div>
                    
  
                    <!-- Modal -->
                    <div class="modal fade" id="attachmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Attachment (Optional)</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    Hi, this feature is currently unavailable. Check back soon for updates.
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div><!--
                <div class="col-sm-6">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home-nobd" role="tabpanel" aria-labelledby="v-pills-home-tab-nobd">
                            <div class="form-group">
                                <form action="{{ route('assessments.user.verbatim',['type' => $assessmentTypeUser->id, 'employeeID' => $assesseeEmployeeID->employeeID, 'modelID' => $modelItem->id]) }}" method="POST" >
                                    @csrf
                                    <label for="email2">Please provide examples of how the competency is being applied. <i>*Optional</i></label>
                                    <div class="input-group">
                                        
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Verbatim</span>
                                        </div>
                                        <textarea name="verbatim" class="form-control" aria-label="With textarea">@if($userStoredEvaluationCompetency){{$userStoredEvaluationCompetency->verbatim}}@endif
                                        </textarea>
                                    </div><br>
                                    <button type="submit" class="btn btn-info">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile-nobd" role="tabpanel" aria-labelledby="v-pills-profile-tab-nobd">
                            <div class="form-group">
                                <form action="">
                                    @csrf
                                    <label for="exampleFormControlFile1">Do you have any Attachment? Upoad it here. <i>*Optional</i> </label>
                                    <input type="file" name="attached-file" class="form-control-file" id="exampleFormControlFile1">
                                    <br>
                                    <button class="btn btn-info">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-sm-4">
                    <div class="row py-1">
                        <div class="col-12"><a href="{{route('assessments.index')}}" class="btn btn-primary btn-border btn-block btn-round">Return to Assessment</a></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4  py-2 bg-globe-blue text-white text-center text-uppercase">
                    Assessee: <b>{{$assesseeEmployeeID->firstname}} {{$assesseeEmployeeID->lastname}}</b>
                </div>
                <div class="col-sm-4 py-2 bg-globe-purple text-white text-center text-uppercase">
                    Role: <b>{{$assesseeRole->name}}</b>
                </div>
                
                <div class="col-sm-4 py-2 bg-globe-light-blue text-white text-center text-uppercase">
                    Assessment Version ID: @if ($userStoredEvaluation)
                        <b>ASM-{{$userStoredEvaluation->id}}</b>
                    @else
                    ASM-New
                    @endif
                </div>
           </div>

            <nav aria-label="...">
                <ul class="pagination justify-content-center my-3">
                    
                    @if($isFirstPage == false)
                        <li class="page-item ">
                            <a class="page-link" href="{{$previous_page}}" >Previous</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                    @endif
                        
                    

                    @foreach($assessmentItems as $modelItemRow)
                        @if($modelItemRow->modelRowID == $modelItem->id)
                            <li class="page-item "><a class="page-link bg-globe-light-blue text-white" href="{{$modelItemRow->modelRowID}}">{{$modelItemRow->id + 1}}</a></li>
                        @else
                            @if ($modelItemRow->answered == 1)
                                <li class="page-item active"><a class="page-link" href="{{$modelItemRow->modelRowID}}">{{$modelItemRow->id + 1}}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{$modelItemRow->modelRowID}}">{{$modelItemRow->id + 1}}</a></li>
                            @endif
                            
                        @endif
                    @endforeach

                    <li class="page-item">
                        @if($isLastPage == false)
                            <a class="page-link" href="{{$next_page}}">Next</a>
                        @else
                        <a class="page-link" href="{{route('assessments.end',['type' => $assessmentTypeUser->id, 'employeeID' => $assesseeEmployeeID->id])}}">Done</a>
                        @endif
                        
                    </li>
                </ul>
            </nav>
        </div>
        
        
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#hello-user').hide(0).delay(250).fadeIn(500);
    });
    </script>
<script>
    $('.competencyItemSection .proficiencyItemCard').click(function() {
        $(this).closest(".competencyItemSection").find('.proficiencyItemCard').removeClass('selected');
        $(this).addClass('selected');
        var val = $(this).attr('data-value');
        $(this).closest(".proficiencyItemCard").find('input').val(val);
        $(this).parent().siblings("#givenLevelID").val(val); 


        var countAccomplishedItems = $('.selected').length;

        var countTotalItems = $('#{{$modelItem->id}}competencyItemSectionProficiencies').length;

        
    });
</script>
<script>
    $(".proficiencyItemCard").click(function(e){
        e.preventDefault();

        var assessmentTypeID = $("#assessmentTypeID").val();
        var assessorEmployeeID = $("#assessorEmployeeID").val();
        var assesseeEmployeeID = $("#assesseeEmployeeID").val();
        var assessorRoleID = $("#assessorRoleID").val();
        var assesseeRoleID = $("#assesseeRoleID").val();
        var modelCompetencyID = $("#modelCompetencyID").val();
        var competencyID = $("#competencyID").val();
        var competencyTypeID = $("#competencyTypeID").val();
        var targetLevelID = $("#targetLevelID").val();
        
        var givenLevelID = $(this).attr('data-value');
        console.log(givenLevelID);
        
        $.ajax({
            
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('assessments.user.store')}}",
            data:{assessmentTypeID:assessmentTypeID, assessorEmployeeID:assessorEmployeeID, assesseeEmployeeID:assesseeEmployeeID,
                assessorRoleID:assessorRoleID, assesseeRoleID:assesseeRoleID, modelCompetencyID:modelCompetencyID,
                competencyID:competencyID,competencyTypeID:competencyTypeID,targetLevelID:targetLevelID,givenLevelID:givenLevelID},
            
            success:function(data){
                $.notify({
                    // options
                    message: 'Assessment Saved'
                },{
                    // settings
                    type: 'success'
                });

                /*
                $('#submitted').show().addClass('slideInUp');
                
                var tId;
                clearTimeout(tId);
                tId=setTimeout(function(){
                $("#submitted").hide().fadeOut();        
                }, 4000);
                */

                //$('#submitted').show();
                //$this.find('.submitted').addClass('slideInUp');
                
                /*swal({
                                title: "Success",
                                text: data.message,
                                icon: "success",
                                button: "Ok",
                            });*/

            },
            error: function (request, status, error) {
                swal({
                    title: "Fail",
                    text: "Something went wrong.",
                    icon: "error",
                    button: "Ok",
                });
            }
        });
    });
</script>
@endsection