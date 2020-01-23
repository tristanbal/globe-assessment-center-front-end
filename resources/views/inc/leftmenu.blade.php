<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        
        <ul class="nav nav-primary">
          <li class="nav-item">
            <a href="{{route('home')}}">
              <i class="fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>  
          
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Components</h4>
          </li>
          @if ($assessmentType)
            @foreach ($employeeRelationship as $employeeRelationshipItem)
                @foreach ($assessmentType as $assessmentTypeItem)    
                    @if ($employeeRelationshipItem->relationshipID == $assessmentTypeItem->relationshipID)
                      <li class="nav-item">
                        <a href="{{route('assessments.index')}}">
                          <i class="icon-notebook"></i>
                          <p>{{$assessmentTypeItem->name}}</p>
                        </a>
                      </li>    
                    @endif
                @endforeach
            @endforeach
          @endif
          
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->