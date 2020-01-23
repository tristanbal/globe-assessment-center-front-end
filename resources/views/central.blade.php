
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ASSESSMENT PORTAL | LOGIN</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="https://globeuniversity.globe.com.ph/assessment-report/globeicon.png" type="image/png">

    <!-- Fonts and icons -->
    <script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/css/fonts.min.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/css/atlantis.css">
</head>
<body class="login">
          
      

    <style type="text/css">
    .bgwawa{
        background-image: url('https://globeuniversity.globe.com.ph/assessment-report/bgg.jpg');
        background-size: cover;
        background-position: left;
        background-attachment: fixed;
    }
</style>

    <div class="container-fluid" >
        <div class="row" style="height:100vh;">
            <div class="col-sm-6 py-4 container d-flex animated fadeIn flex-column justify-content-center "
                style="background: url('assessment-images/backgrounds/bg home.jpg') no-repeat center center fixed;
                background-size: ;height:100vh">
                <div class="row">
                    <div class="">
                        <a href="https://globeuniversity.globe.com.ph/assessment-center-admin/"><img src="{{asset('assessment-images/GULOGOneg.png')}}" alt="Globe University Logo" style="height:20vh" class="" /></a>
                        
                    </div>
                </div>
                <div class="row">
                    <h1 class=" display-4 text-uppercase pb-2 text-white font-weight-bold col-8">Assessment Portal 2019</h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3 class="font-weight-light text-white ">Let's Start your assessment.</h3>
                    </div>
                </div>
                
                
                <div class="mt-3">
                    <a href="{{ route('login.google') }}" class="btn btn-light btn-round text-uppercase font-weight-bold"><span class="fab fa-google-plus-g"></span>&nbsp;&nbsp;&nbsp; Login with Google</a>
                </div>
            </div>
            <div style="" class="col-sm-6 " style="height:100vh;">
                <div class="row" style="height:50vh;">
                    <div class="col-sm-12 py-4  text-white container d-flex align-items-center justify-content-center bg-white"
                    style="background: url('assessment-images/backgrounds/loginBackgroundPicture.jpg') no-repeat center center fixed;
                    background-size: cover;">
                        <div class="container  container-transparent animated fadeIn">
                            <form method="POST" action="https://globeuniversity.globe.com.ph/assessment-report/login">
                            <input type="hidden" name="_token" value="10P8OBs6k8wM5eGVExfq4iSKbshcdi7F7Syc5Jr9">    
                            <div class="row">
                                <h1 class="font-weight-bold col-8">Self-Service Reports Generations</h1>    
                            </div>           
                            <div class="row">
                                <h3 class="font-weight-light col-12">Looking for your reports?</h3>
                            </div>           
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <!--<a href="https://globeuniversity.globe.com.ph/assessment-center-service-report/redirect" class="btn btn-light btn-round text-uppercase font-weight-bold"><span class="fab fa-google-plus-g"></span>&nbsp;&nbsp;&nbsp; Access Reports</a>-->
                                    <!--<a href="https://globeuniversity.globe.com.ph/assessment-report/redirect" class="btn" style="background-color: #fafafa; border: .5px solid; letter-spacing: 1px; font-size: 17px;"><img src="https://globeuniversity.globe.com.ph/assessment-report/logos/googleLogo.png" style="width: 20px;">&nbsp;&nbsp;&nbsp; Access Reports</a>-->
                                    <button type="button" class="btn btn-light btn-round text-uppercase font-weight-bold" data-toggle="modal" data-target="#exampleModal">
                                        <span class="fab fa-google-plus-g"></span>&nbsp;&nbsp;&nbsp; Access Reports
                                    </button>
                                </div>
                            </div>
                            <br>
                            
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row" style="height:50vh;">
                    <div class="col-sm-12 py-4 bg-danger text-white container d-flex align-items-center justify-content-center bg-white"
                        style="background: url('assessment-images/backgrounds/bg-red.jpg') no-repeat center center fixed;
                        background-size: cover;">
                        <div class="container  container-transparent animated fadeIn">
                            <input type="hidden" name="_token" value="10P8OBs6k8wM5eGVExfq4iSKbshcdi7F7Syc5Jr9">               
                            <div class="row">
                                <h1 class="font-weight-bold col-8">Competency Model Builder</h1>    
                            </div>           
                            <div class="row">
                                <h3 class="font-weight-light col-12">Create a new assessment model (Subject for approval).</h3>
                            </div>           
                            <br>
                            <div class="">
                                <a href="https://globeuniversity.globe.com.ph/assessment-model/login/google" class="btn btn-light btn-round text-uppercase font-weight-bold"><span class="fab fa-google-plus-g"></span>&nbsp;&nbsp;&nbsp; Access Model Builder</a>
                                <!--<a href="https://globeuniversity.globe.com.ph/assessment-report/redirect" class="btn" style="background-color: #fafafa; border: .5px solid; letter-spacing: 1px; font-size: 17px;"><img src="https://globeuniversity.globe.com.ph/assessment-report/logos/googleLogo.png" style="width: 20px;">&nbsp;&nbsp;&nbsp; Access Reports</a>-->
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Temporarily Unavailable</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            Good day ka-Globe! The reports are currently unavailable due to a system maintenance. Check back soon for updates.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/core/jquery.3.2.1.min.js"></script>
<script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/core/popper.min.js"></script>
<script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/core/bootstrap.min.js"></script>
<script src="https://globeuniversity.globe.com.ph/assessment-report/atlantis/assets/js/atlantis.min.js"></script>
</html>
