<?php
if (!isset($_GET['id']) || !isset($_GET['cat'])) {
    header('Location: index.php');
} else {
    include "includes/config.php";
    $post_id = intval($_GET['id']);
    $stmt = $pdo_conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    $category = $_GET['cat'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($post['title']) ?> | MyBlog</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <a href="./index.php?pass=1" class="btn btn-secondary btn-sm">Back to Home</a>
                </nav>

                <!-- Post Content -->
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <?php if ($post['image']): ?>
                            <img src="<?= $post['image'] ?>" class="card-img-top w-25" alt="<?= $post['title'] ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge mb-2"><?= $category  ?></span>
                            <h2 class="card-title"><?= $post['title'] ?></h2>
                            <small class="text-muted">Published on <?= $post['created_at'] ?></small>
                            <hr>
                            <p class="card-text"><?= $post['content'] ?></p>
                            <div class="comments">
                                <h3>Comments</h3>
                                <?php
                                $stmt = $pdo_conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
                                $stmt->execute([$post_id]);
                                $comments = $stmt->fetchAll();

                                if (count($comments) > 0): ?>
                                    <?php foreach ($comments as $comment): ?>
                                        <?php if ($comment['status'] == 'approved'): ?>
                                            <?php
                                            $userSTMT = $pdo_conn->prepare("SELECT * FROM users WHERE id = ?");
                                            $userSTMT->execute([$comment['user_id']]);
                                            $user = $userSTMT->fetch();
                                            ?>
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-2 fw-bold">
                                                        <?= $user['Name'] ?>
                                                        <small><?= $comment['created_at'] ?></small>
                                                    </h6>

                                                    <p class="card-text"><?= $comment['comment'] ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No comments yet. Be the first to comment!</p>
                                <?php endif; ?>
                                <hr>
                                <form action="addcomment.php?cat=<?= $category ?>" method="post" class="mt-4">
                                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                    <div class="form-group">
                                        <input type="text" name="author" class="form-control mb-2" placeholder="Your name" value="<?= $_COOKIE['username'] ?>" required>
                                        <textarea name="content" class="form-control mb-2" rows="3" placeholder="Your comment" required></textarea>
                                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white text-center">
                <span>Copyright &copy; MyBlog 2025</span>
            </footer>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>