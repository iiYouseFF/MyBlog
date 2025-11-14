<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../includes/config.php";
// Require login + admin privileges. Central helpers defined in includes/config.php
require_login();
require_admin();

$username = $currentUser;

// Fetch all posts
$commentsStmt = $pdo_conn->query("SELECT * FROM comments");
$comments = $commentsStmt->fetchAll();
$commentsNum = count($comments);

// Fetch pending comments only
$pendingStmt = $pdo_conn->query("SELECT * FROM comments WHERE status = 'pending' ORDER BY id DESC");
$pendingComments = $pendingStmt->fetchAll();
$pendingNum = count($pendingComments);
?>




<?php include "../includes/header.php" ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        $page = "comments";
        include "../includes/sidebar.php";
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $username ?></span>
                                <i class="fas fa-solid fa-user"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="index.php">
                                    <i class="fas fa-tachometer-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Dashboard
                                </a>
                                <a class="dropdown-item" href="../index.php?pass=1">
                                    <i class="fas fa-globe fa-sm fa-fw mr-2 text-gray-400"></i>
                                    View Site
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Comments Management</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example (Comments) -->
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Comments</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $commentsNum ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($pendingNum > 0): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 bg-warning">
                                <h6 class="m-0 font-weight-bold text-white">Pending Comments</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Post Title</th>
                                                <th>User Name</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pendingComments as $comment): ?>
                                                <?php
                                                $userStmt = $pdo_conn->query('SELECT * From users WHERE id = ' . $comment['user_id']);
                                                $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                                                $username = $user['Name'];
                                                $postStmt = $pdo_conn->query('SELECT * From posts WHERE id = ' . $comment['post_id']);
                                                $post = $postStmt->fetch(PDO::FETCH_ASSOC);
                                                $postTitle = $post['title'];
                                                ?>
                                                <tr>
                                                    <td><?= $comment["id"] ?></td>
                                                    <td><?= $postTitle ?></td>
                                                    <td><?= $username ?></td>
                                                    <td><?= $comment["comment"] ?></td>
                                                    <td><?= $comment["status"] ?></td>
                                                    <td><?= $comment["created_at"] ?></td>
                                                    <td>
                                                        <a class="btn btn-success" href="approvecomment.php?id=<?= $comment['id'] ?>">Approve</a>
                                                        <a class="btn btn-danger" href="rejectcomment.php?id=<?= $comment['id'] ?>">Reject</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Comments Table</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Post Title</th>
                                            <th>User Name</th>
                                            <th>Comment</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($comments as $comment): ?>

                                            <?php
                                            // Fetch User
                                            $userStmt = $pdo_conn->query('SELECT * FROM users WHERE id = ' . (int)$comment['user_id']);
                                            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
                                            $username = $user ? $user['name'] : "Unknown User";

                                            // Fetch Post
                                            $postStmt = $pdo_conn->query('SELECT * FROM posts WHERE id = ' . (int)$comment['post_id']);
                                            $post = $postStmt->fetch(PDO::FETCH_ASSOC);
                                            $postTitle = $post ? $post['title'] : "Deleted Post";
                                            ?>

                                            <tr>
                                                <td><?= $comment["id"] ?></td>
                                                <td><?= $postTitle ?></td>
                                                <td><?= $username ?></td>
                                                <td><?= $comment["comment"] ?></td>
                                                <td><?= $comment["status"] ?></td>
                                                <td><?= $comment["created_at"] ?></td>
                                                <td>
                                                    <?php if ($post): ?>
                                                        <a href="deletepost.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm mb-1"
                                                        onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                                    <?php else: ?>
                                                        <span class="text-muted">No post</span>
                                                    <?php endif ?>
                                                </td>
                                            </tr>

                                        <?php endforeach ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Myblog 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



    <?php include "../includes/footer.php" ?>