 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand mi-nav-color navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link">User: <span class="text-light">Super Admin Account!</span></a>
      </li>      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
 
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" href="notifications.php">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>        
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> Profile         
        </a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
        <a href="{{ route('user.edit-profile') }}" class="dropdown-item">
            Edit Profile           
          </a>          
          <div class="dropdown-divider"></div>          
          <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
            @csrf
            <button type="submit">Logout</button>
        </form> 
        </div>
      </li>


    </ul>
  </nav>
  <!-- /.navbar -->