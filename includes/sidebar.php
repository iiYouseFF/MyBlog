 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
         <div class="sidebar-brand-icon rotate-n-15">
             <i class="fas fa-laugh-wink"></i>
         </div>
         <div class="sidebar-brand-text mx-3">MyBlog</div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard -->
     <li class="nav-item <?php if ($page == "dashboard") echo "active" ?>">
         <a class="nav-link" href="index.php">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Heading -->
     <div class="sidebar-heading">
         Content
     </div>

     <!-- Nav Item - Posts -->
     <li class="nav-item <?php if ($page == "posts") echo "active" ?>">
         <a class="nav-link collapsed" href="posts.php">
             <i class="fas fa-solid fa-file"></i>
             <span>Posts</span>
         </a>
     </li>

     <!-- Nav Item - Categories -->
     <li class="nav-item <?php if ($page == "categories") echo "active" ?>">
         <a class="nav-link collapsed" href="categories.php">
             <i class="fas fa-solid fa-list"></i>
             <span>Categories</span>
         </a>
     </li>
     <!-- Nav Item - Comments -->
     <li class="nav-item <?php if ($page == "comments") echo "active" ?>">
         <a class="nav-link collapsed" href="comments.php">
             <i class="fas fa-solid fa-comments"></i>
             <span>Comments</span>
         </a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Heading -->
     <div class="sidebar-heading">
         Management
     </div>

     <!-- Nav Item - Users -->
     <li class="nav-item <?php if ($page == "users") echo "active" ?>">
         <a class="nav-link collapsed" href="users.php">
             <i class="fas fa-fw fa-user"></i>
             <span>Users</span>
         </a>
     </li>
     <li class="nav-item <?php if ($page == "adminrequests") echo "active" ?>">
         <a class="nav-link collapsed" href="requests.php">
             <i class="fas fa-solid fa-hand-point-up"></i>
             <span>Admin Requests</span>
         </a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <div class="sidebar-heading">
         Other
     </div>

     <!-- Nav Item - Users -->
     <li class="nav-item">
         <a class="nav-link collapsed" href="../index.php?pass=1">
             <i class="fas fa-solid fa-globe"></i>
             <span>View Site</span>
         </a>
     </li>

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>

 </ul>
 <!-- End of Sidebar -->