<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './includes/config.php';
$errors = [];
$message = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $content = trim($_POST['content'] ?? '');
    // Validate inputs
    if ($title === '') {
        $errors[] = "Title is required.";
    }
    if ($category === '') {
        $errors[] = "Category is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }
    if (!isset($_FILES['image'])) {
        $errors[] = "Image upload failed.";
    }
    $targetDir = "uploads/";
    $targetFile = $targetDir . $_FILES['image']['name'];
    if (!getimagesize($_FILES['image']['tmp_name'])) {
        $errors[] = "Uploaded File Is Not Image";
    }
    if (empty($errors)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $pdo_conn->prepare("INSERT INTO posts (title, category_id, image, content ,status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $targetFile, $content, 'draft']);
            $message = '<div class="alert alert-success">Post added successfully!</div>';
            header("Location:index.php");
        } else {
            $errors[] = "Failed to move uploaded file.";
        }
    }
    if (!empty($errors)) {
        $message = '<div class="alert alert-danger"><ul><li>' . implode('</li><li>', $errors) . '</li></ul></div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="col-md-8 mx-auto">
            <?= $message ?>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add New Post</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="<?= $_POST['title'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php
                                $catSTMT = $pdo_conn->query("SELECT name , id FROM categories");
                                $categories = $catSTMT->fetchAll();
                                foreach ($categories as $cat):
                                ?>
                                    <option value="<?= htmlspecialchars($cat['id']) ?>" <?= (isset($_POST['category']) && $_POST['category'] === $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="7" required><?= $_POST['content'] ?? '' ?></textarea>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Publish Post</button>
                        <a href="index.php" class="btn btn-secondary w-100 mt-2">Back to Home</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>