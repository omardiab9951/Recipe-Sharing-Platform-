<?php
session_start();
require_once 'config/database.php';

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($recipe_id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch recipe details with user information
try {
    $stmt = $pdo->prepare("
        SELECT r.*, u.name as author_name, u.id as author_id 
        FROM recipes r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.id = ?
    ");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching recipe: " . $e->getMessage());
}

$page_title = $recipe['title'] . ' - Recipe Sharing Platform';
include 'includes/header.php';

// Check if current user is the recipe owner
$is_owner = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['author_id']) {
    $is_owner = true;
}

// Get emoji based on category
function getCategoryEmoji($category)
{
    $emojis = [
        'Breakfast' => '🥞',
        'Lunch' => '🥗',
        'Dinner' => '🍽️',
        'Dessert' => '🍰',
        'Snack' => '🍿',
        'Beverage' => '🥤'
    ];
    return isset($emojis[$category]) ? $emojis[$category] : '🍴';
}
?>

<style>
    .recipe-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .recipe-header {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
    }

    .recipe-title {
        font-size: 42px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
    }

    .recipe-meta {
        display: flex;
        gap: 30px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f3f4f6;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 16px;
    }

    .category-badge {
        display: inline-block;
        padding: 8px 20px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .recipe-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .recipe-image-section {
        position: relative;
    }

    .recipe-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .no-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 120px;
    }

    .recipe-details-box {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 28px;
        background: #d97706;
        border-radius: 2px;
    }

    .ingredients-list {
        list-style: none;
        padding: 0;
    }

    .ingredients-list li {
        padding: 12px 15px;
        margin-bottom: 8px;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 3px solid #d97706;
        font-size: 16px;
        color: #374151;
    }

    .instructions-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
    }

    .instructions-list {
        list-style: none;
        counter-reset: step-counter;
        padding: 0;
    }

    .instructions-list li {
        counter-increment: step-counter;
        position: relative;
        padding: 20px 20px 20px 70px;
        margin-bottom: 15px;
        background: #f9fafb;
        border-radius: 10px;
        font-size: 16px;
        color: #374151;
        line-height: 1.8;
    }

    .instructions-list li::before {
        content: counter(step-counter);
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 35px;
        height: 35px;
        background: #d97706;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .btn-back {
        background: #6b7280;
        color: white;
    }

    .btn-back:hover {
        background: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 992px) {
        .recipe-content {
            grid-template-columns: 1fr;
        }

        .recipe-image {
            height: 350px;
        }
    }

    @media (max-width: 768px) {
        .recipe-header {
            padding: 30px 20px;
        }

        .recipe-title {
            font-size: 32px;
        }

        .recipe-meta {
            gap: 15px;
        }

        .recipe-image {
            height: 300px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .recipe-detail-container {
            padding: 20px 10px;
        }

        .recipe-title {
            font-size: 28px;
        }

        .recipe-image {
            height: 250px;
        }
    }
</style>

<div class="recipe-detail-container">

    <!-- Recipe Header -->
    <div class="recipe-header">
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>

        <span class="category-badge">
            <?php echo getCategoryEmoji($recipe['category']) . ' ' . htmlspecialchars($recipe['category']); ?>
        </span>

        <div class="recipe-meta">
            <div class="meta-item">
                <strong>👤 Author:</strong> <?php echo htmlspecialchars($recipe['author_name']); ?>
            </div>
            <div class="meta-item">
                <strong>📅 Posted:</strong> <?php echo date('F j, Y', strtotime($recipe['created_at'])); ?>
            </div>
        </div>
    </div>

    <!-- Recipe Content: Image + Ingredients -->
    <div class="recipe-content">

        <!-- Recipe Image -->
        <div class="recipe-image-section">
            <?php if (!empty($recipe['image']) && file_exists('assets/uploads/' . $recipe['image'])): ?>
                <img src="assets/uploads/<?php echo htmlspecialchars($recipe['image']); ?>"
                    alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                    class="recipe-image">
            <?php else: ?>
                <div class="no-image">
                    <?php echo getCategoryEmoji($recipe['category']); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ingredients -->
        <div class="recipe-details-box">
            <h2 class="section-title">🛒 Ingredients</h2>
            <ul class="ingredients-list">
                <?php
                $ingredients = explode("\n", $recipe['ingredients']);
                foreach ($ingredients as $ingredient):
                    $ingredient = trim($ingredient);
                    if (!empty($ingredient)):
                ?>
                        <li><?php echo htmlspecialchars($ingredient); ?></li>
                <?php
                    endif;
                endforeach;
                ?>
            </ul>
        </div>

    </div>

    <!-- Instructions -->
    <div class="instructions-section">
        <h2 class="section-title">📝 Instructions</h2>
        <ol class="instructions-list">
            <?php
            $instructions = explode("\n", $recipe['instructions']);
            foreach ($instructions as $instruction):
                $instruction = trim($instruction);
                // Remove "Step X:" prefix if exists
                $instruction = preg_replace('/^Step\s+\d+:\s*/i', '', $instruction);
                // Remove numbering like "1." or "1)"
                $instruction = preg_replace('/^\d+[\.\)]\s*/', '', $instruction);

                if (!empty($instruction)):
            ?>
                    <li><?php echo htmlspecialchars($instruction); ?></li>
            <?php
                endif;
            endforeach;
            ?>
        </ol>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="index.php" class="btn btn-back">⬅️ Back to Recipes</a>

        <?php if ($is_owner): ?>
            <a href="edit-recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary">✏️ Edit Recipe</a>
            <a href="delete-recipe.php?id=<?php echo $recipe['id']; ?>"
                class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this recipe?')">
                🗑️ Delete Recipe
            </a>
        <?php endif; ?>
    </div>

</div>

<?php include 'includes/footer.php'; ?>