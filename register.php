<?php
session_start();
require_once 'config/database.php';

$page_title = 'Register - Recipe Sharing Platform';
$error = '';
$success = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? trim($_POST['password']) : '';
  $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

  // Validation
  if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    $error = 'Please fill in all fields';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address';
  } elseif (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters long';
  } elseif ($password !== $confirm_password) {
    $error = 'Passwords do not match';
  } else {
    try {
      // Check if email already exists
      $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
      $stmt->execute([$email]);

      if ($stmt->fetch()) {
        $error = 'Email already registered. Please login instead.';
      } else {
        // Create new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");

        if ($stmt->execute([$name, $email, $hashed_password])) {
          // Get the new user's ID
          $user_id = $pdo->lastInsertId();

          // AUTO-LOGIN: Set session variables
          $_SESSION['user_id'] = $user_id;
          $_SESSION['user_name'] = $name;
          $_SESSION['user_email'] = $email;
          $_SESSION['user_role'] = 'user';

          // Redirect to home page immediately
          header('Location: index.php');
          exit;
        } else {
          $error = 'Registration failed. Please try again.';
        }
      }
    } catch (PDOException $e) {
      $error = 'Database error. Please try again later.';
    }
  }
}

include 'includes/header.php';
?>

<style>
  .register-page {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 60px 20px;
  }

  .register-container {
    width: 100%;
    max-width: 500px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    padding: 50px 40px;
  }

  .register-header {
    text-align: center;
    margin-bottom: 40px;
  }

  .register-title {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 10px 0;
  }

  .register-subtitle {
    color: #6b7280;
    font-size: 16px;
    margin: 0;
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

  .form-input {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s;
    box-sizing: border-box;
  }

  .form-input:focus {
    outline: none;
    border-color: #d97706;
    box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
  }

  .form-input::placeholder {
    color: #9ca3af;
  }

  .error-message {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #991b1b;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 14px;
  }

  .success-message {
    background: #d1fae5;
    border-left: 4px solid #10b981;
    color: #065f46;
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 14px;
  }

  .btn-register {
    width: 100%;
    padding: 16px;
    background: #d97706;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 10px;
  }

  .btn-register:hover {
    background: #b45309;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(217, 119, 6, 0.3);
  }

  .login-link {
    text-align: center;
    margin-top: 25px;
    color: #6b7280;
    font-size: 15px;
  }

  .login-link a {
    color: #3b82f6;
    font-weight: 600;
    text-decoration: none;
  }

  .login-link a:hover {
    text-decoration: underline;
  }

  .password-hint {
    font-size: 13px;
    color: #6b7280;
    margin-top: 5px;
  }

  @media (max-width: 576px) {
    .register-container {
      padding: 40px 30px;
    }

    .register-title {
      font-size: 28px;
    }
  }
</style>

<div class="register-page">
  <div class="register-container">

    <div class="register-header">
      <h1 class="register-title">Create Your Account</h1>
      <p class="register-subtitle">Join our cooking community today!</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="error-message">
        ⚠️ <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="success-message">
        ✅ <?php echo htmlspecialchars($success); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="register.php">

      <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-input"
          placeholder="Enter your full name"
          required
          value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input"
          placeholder="your.email@example.com"
          required
          value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-input"
          placeholder="Create a strong password"
          required>
        <p class="password-hint">Must be at least 6 characters long</p>
      </div>

      <div class="form-group">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input
          type="password"
          id="confirm_password"
          name="confirm_password"
          class="form-input"
          placeholder="Re-enter your password"
          required>
      </div>

      <button type="submit" class="btn-register">
        Create Account
      </button>

    </form>

    <div class="login-link">
      Already have an account? <a href="login.php">Login here</a>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>