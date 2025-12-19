<?php
session_start();
require_once 'config/database.php';

$page_title = 'Add Recipe - Recipe Sharing Platform';
$error = '';
$success = '';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);

    // Validation
    if (empty($title) || empty($category) || empty($ingredients) || empty($instructions)) {
        $error = 'Please fill in all required fields';
    } else {
        // Handle image upload
        $image_name = 'default-recipe.jpg';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($filetype), $allowed)) {
                $image_name = time() . '_' . $filename;
                $upload_path = 'assets/uploads/' . $image_name;

                // Create uploads folder if it doesn't exist
                if (!file_exists('assets/uploads')) {
                    mkdir('assets/uploads', 0777, true);
                }

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $error = 'Failed to upload image';
                }
            } else {
                $error = 'Invalid image format. Only JPG, JPEG, PNG, and GIF allowed';
            }
        }

        // Insert recipe if no errors
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO recipes (user_id, title, category, ingredients, instructions, image, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");

                if ($stmt->execute([$_SESSION['user_id'], $title, $category, $ingredients, $instructions, $image_name])) {
                    $recipe_id = $pdo->lastInsertId();
                    header("Location: recipe-detail.php?id=" . $recipe_id);
                    exit;
                } else {
                    $error = 'Failed to add recipe. Please try again.';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

include 'includes/header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 60px 0;
        text-align: center;
    }

    .page-header h1 {
        color: #92400e;
        margin: 0;
        font-size: 36px;
    }

    .page-header p {
        color: #78350f;
        margin: 10px 0 0 0;
        font-size: 18px;
    }

    .form-section {
        padding: 40px 0;
        background: #f9fafb;
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 15px;
    }

    input[type="text"],
    input[type="file"],
    textarea,
    select {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
        box-sizing: border-box;
        font-family: inherit;
    }

    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #d97706;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
    }

    input::placeholder,
    textarea::placeholder {
        color: #9ca3af;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    select option[value=""] {
        color: #9ca3af;
    }

    .alert {
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 8px;
    }

    .alert-error {
        background: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .alert-success {
        background: #d1fae5;
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .form-actions .btn {
        flex: 1;
    }

    .btn {
        display: inline-block;
        padding: 14px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #d97706;
        color: white;
    }

    .btn-primary:hover {
        background: #b45309;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(217, 119, 6, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 30px 20px;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<section class="page-header">
    <div class="container">
        <h1>Share Your Recipe</h1>
        <p>Add a new recipe to share with the community</p>
    </div>
</section>

<section class="form-section">
    <div class="container">
        <div class="form-container">

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <strong>✅ Success:</strong> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="add-recipe.php" enctype="multipart/form-data" class="recipe-form">

                <div class="form-group">
                    <label for="title" class="form-label">Recipe Title *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        required
                        value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                        placeholder="e.g., Chocolate Chip Cookies">
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="Breakfast" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Breakfast') ? 'selected' : ''; ?>>🥞 Breakfast</option>
                        <option value="Lunch" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Lunch') ? 'selected' : ''; ?>>🥗 Lunch</option>
                        <option value="Dinner" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Dinner') ? 'selected' : ''; ?>>🍝 Dinner</option>
                        <option value="Dessert" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Dessert') ? 'selected' : ''; ?>>🍰 Dessert</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ingredients" class="form-label">Ingredients *</label>
                    <textarea
                        id="ingredients"
                        name="ingredients"
                        rows="6"
                        required
                        placeholder="List all ingredients, one per line..."><?php echo isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="instructions" class="form-label">Instructions *</label>
                    <textarea
                        id="instructions"
                        name="instructions"
                        rows="8"
                        required
                        placeholder="Write step-by-step instructions..."><?php echo isset($_POST['instructions']) ? htmlspecialchars($_POST['instructions']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">Recipe Image (Optional)</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*">
                    <small style="color: #6b7280; font-size: 13px; display: block; margin-top: 8px;">
                        📎 Accepted formats: JPG, JPEG, PNG, GIF (Max 5MB)
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✨ Add Recipe
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>