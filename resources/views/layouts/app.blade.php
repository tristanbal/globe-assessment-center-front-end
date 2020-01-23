<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
	<title>ASSESSMENT PORTAL</title>
	@include('inc.templates')

</head>
<body>
	<div class="wrapper sidebar_minimize">
	@include('inc.navbar')
    @include('inc.leftmenu')
        <div class="content">
                
            @yield('content')
            
        </div>
        
    </div>
    
  @include('inc.scripts')
  @yield('scripts')
</body>
</html>