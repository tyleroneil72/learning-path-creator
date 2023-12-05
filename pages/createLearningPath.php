<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container mt-5">
    <h1>Create Learning Path</h1>

    <form action="../db/createLearningPath.php" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="resources">Resources (URLs, separated by commas)</label>
            <input type="text" class="form-control" id="resources" name="resources" required>
        </div>

        <button type="submit" class="btn btn-primary" name="createLearningPath">Create Learning Path</button>
    </form>
</div>

<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php'; ?>