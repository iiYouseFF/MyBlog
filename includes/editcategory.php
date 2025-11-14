<?php
// ✅ UPDATE CATEGORY WHEN FORM SUBMITTED
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {

    $id = (int) $_POST['edit_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name !== "" && $description !== "") {
        $update = $pdo_conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $update->execute([$name, $description, $id]);

        // ✅ Redirect back with no parameters (hide form)
        header("Location: categories.php");
        exit;
    }
}
?>

<div class="card shadow mb-4 w-100">
    <div class="card-header">
        <h4 class="mb-0 text-primary">Edit Category #<?= $editCategory['id'] ?></h4>
    </div>
    <div class="card-body">

        <form method="POST">

            <input type="hidden" name="edit_id" value="<?= $editCategory['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($editCategory['name']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                    required><?= htmlspecialchars($editCategory['description']) ?></textarea>
            </div>

            <button class="btn btn-warning w-100">Save Changes</button>

        </form>

    </div>
</div>