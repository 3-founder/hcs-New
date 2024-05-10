<!--
=========================================================
* Paper Dashboard 2 - v2.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/paper-dashboard-2
* Copyright 2020 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('style/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('style/assets/img/logo.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
  Human Capital System | BPR SARIBUMI
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="{{ asset('style/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/css/paper-dashboard.css') }}" rel="stylesheet" />
  <link href="{{ asset('style/assets/demo/demo.css') }}" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="" class="simple-text logo-mini">
          <div class="logo-image-small">
            <img src="{{ asset('style/assets/img/logo.png') }}">
          </div>
        </a>
        <a href="" class="simple-text logo-normal">
          Bio Interface
        </a>
      </div>
      <div class="dropdown sidebar-wrapper">
        <ul class="nav">
            <li class="">
                <a href="/home">
                    <i class="nc-icon nc-bank"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
                <a class="nav-link disabled">
                    <p>Navigation</p>
                </a>
            </li>
             <li class="dropdown">
                    <a href="" data-toggle="dropdown" aria-expanded="false">
                        <i class="nc-icon nc-badge"></i>
                        <p class="dropdown-toggle" id="navbarDropdownMenuLink">Karyawan </p>
                        <p></p>
                    </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Data Karyawan</a>
                    <a class="dropdown-item" href="#">Mutasi</a>
                    <a class="dropdown-item" href="#">Demosi</a>
                    <a class="dropdown-item" href="#">Promosi</a>
                </div>
            </li>
            <li class="active">
                <a href="/data_master">
                    <i class="nc-icon nc-briefcase-24"></i>
                    <p>Master Data</p>
                </a>
            </li>
            <li>
                <a href="/data_table">
                <i class="nc-icon nc-paper"></i>
                <p>Laporan</p>
                </a>
            </li>
            <li class="active-pro">
                <a href="">
                <p class="text-center">BANK BPR JATIM</p>
                </a>
            </li>
        </ul>
      </div>
      
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar fixed-top navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="card m-sm-1  navbar-nav">
              <li class="nav-item btn-rotate">
                  <a class="nav-link noHover">
                    <i class="nc-icon nc-watch-time"></i>
                    <p id="DisplayClock" class="" onload="showTime()"></p>
                  </a>
              </li>
            </ul>
          <ul class="card m-sm-1 navbar-nav">
            <li class="nav-item btn-rotate dropdown">
                <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="nc-icon nc-single-02"></i>
                  <p>Halo, Jatim</p>
                  <p></p>
                </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Logout</a>
              </div>
            </li>
          </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="card">
          <div class="card-header">
            <div class="card-header">
              <h5 class="card-title">Data Master</h5>
            </div>
          </div>
          <div class="card-body">
            <form>
              <div class="row">
                <div class="col-md-5 pr-1">
                  <div class="form-group">
                    <label>Company (disabled)</label>
                    <input type="text" class="form-control" disabled="" placeholder="Company" value="">
                  </div>
                </div>
                <div class="col-md-3 px-1">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" placeholder="Username" value="">
                  </div>
                </div>
                <div class="col-md-4 pl-1">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" placeholder="Email">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 pr-1">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" placeholder="Company" value="">
                  </div>
                </div>
                <div class="col-md-6 pl-1">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" placeholder="Last Name" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" placeholder="Home Address" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 pr-1">
                  <div class="form-group">
                    <label>City</label>
                    <input type="text" class="form-control" placeholder="City" value="">
                  </div>
                </div>
                <div class="col-md-4 px-1">
                  <div class="form-group">
                    <label for="inputGroupSelect01">Options</label>
                    <select class="form-control" id="inputGroupSelect01">
                      <option selected>Choose...</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div> 
                </div>
                <div class="col-md-4 pl-1">
                  <div class="form-group">
                    <label>Postal Code</label>
                    <input type="number" class="form-control" placeholder="ZIP Code">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>About Me</label>
                    <textarea class="form-control textarea" placeholder="Describe Your Self"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="update ml-auto mr-auto">
                  <button type="submit" class="btn btn-success btn-round">Update Profile</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            <div class="credits ml-auto">
              <span class="copyright">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, made with <i class="fa fa-heart heart"></i> by 3-Founder
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('style/assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('style/assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{ asset('style/assets/js/plugins/chartjs.min.js') }}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('style/assets/js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('style/assets/js/paper-dashboard.min.js') }}" type="text/javascript"></script>
  <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('style/assets/demo/demo.js') }}"></script>
  <!-- Jam Realtime -->
  <script src="{{ asset('style/assets/js/jam.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initChartsPages();
    });
  </script>
</body>

</html>
