<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../includes/config.php";
// Central auth helpers: require login and admin rights
require_login();
require_admin();

$username = $currentUser;

// -------------------------------
// Handle Add Category
// -------------------------------
$showAddForm = false;
if (isset($_GET['add']) && $_GET['add'] == 1) {
    $showAddForm = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name !== "" && $description !== "") {
        $stmt = $pdo_conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);

        header("Location: categories.php"); // refresh page
        exit;
    } else {
        $showAddForm = true; // show form again if validation fails
    }
}

// -------------------------------
// Handle Edit Category
// -------------------------------
$editCategory = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo_conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$editId]);
    $editCategory = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $updateId = intval($_POST['edit_id']);
    $uName = trim($_POST['name_update'] ?? '');
    $uDescription = trim($_POST['description_update'] ?? '');

    if ($updateId > 0 && $uName !== '' && $uDescription !== '') {
        $stmt = $pdo_conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$uName, $uDescription, $updateId]);

        header("Location: categories.php"); // refresh page
        exit;
    } else {
        $editCategory = ['id' => $updateId, 'name' => $uName, 'description' => $uDescription];
    }
}

// -------------------------------
// Fetch Categories
// -------------------------------
$stmt = $pdo_conn->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categoriesNum = count($categories);

?>

<?php include "../includes/header.php"; ?>

<body id="page-top">
    <div id="wrapper">
        <?php
        $page = "categories";
        include "../includes/sidebar.php";
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#">
                                <span class="mr-2 text-gray-600 small"><?= $username ?></span>
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">

                    <div class="d-flex justify-content-between mb-4">
                        <h1 class="h3 text-gray-800">Categories Management</h1>
                    </div>

                    <!-- Card showing total categories -->
                    <div class="col-xl-12 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Categories</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $categoriesNum ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-list fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Category Button -->
                    <a href="categories.php?add=1" class="btn btn-primary my-3">
                        <i class="fas fa-plus-circle"></i> Add New Category
                    </a>

                    <!-- Add Form -->
                    <?php if ($showAddForm): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h4 class="mb-0 text-primary">Add New Category</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="add">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" name="name" class="form-control" required value="<?= $_POST['name'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="4" required><?= $_POST['description'] ?? '' ?></textarea>
                                    </div>
                                    <button class="btn btn-primary">Add Category</button>
                                    <a href="categories.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Form -->
                    <?php if ($editCategory): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h4 class="mb-0 text-dark">Edit Category #<?= $editCategory['id'] ?></h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="edit_id" value="<?= $editCategory['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" name="name_update" class="form-control" required value="<?= htmlspecialchars($editCategory['name']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description_update" class="form-control" rows="4" required><?= htmlspecialchars($editCategory['description']) ?></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning mx-3">Update Category</button>
                                        <a href="categories.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Categories Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Categories Table</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?= $category['id'] ?></td>
                                            <td><?= htmlspecialchars($category['name']) ?></td>
                                            <td><?= htmlspecialchars($category['description']) ?></td>
                                            <td><?= $category['created_at'] ?></td>
                                            <td>
                                                <a href="categories.php?edit=<?= $category['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="deletecategory.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <?php include "../includes/footer.php"; ?>