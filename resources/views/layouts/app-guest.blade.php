<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>ASSESSMENT PORTAL - Admin</title>
	@include('inc.templates')

</head>
<body>
	<div class="wrapper">
	@include('inc.navbar-guest')
        <div class="content">
            @include('inc.messages')     
            @yield('content')
            
        </div>
        
    </div>
    
  @include('inc.scripts')
  @yield('scripts')
</body>
</html>