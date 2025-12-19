<?php
session_start();
require_once 'config/database.php';

$page_title = 'Login - Recipe Sharing Platform';
$error = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? trim($_POST['password']) : '';

  if (empty($email) || empty($password)) {
    $error = 'Please fill in all fields';
  } else {
    try {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = isset($user['role']) ? $user['role'] : 'user';

        header('Location: index.php');
        exit;
      } else {
        $error = 'Invalid email or password';
      }
    } catch (PDOException $e) {
      $error = 'Database error. Please try again later.';
    }
  }
}

include 'includes/header.php';
?>

<style>
  .login-page {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 60px 20px;
  }

  .login-container {
    width: 100%;
    max-width: 450px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    padding: 50px 40px;
  }

  .login-header {
    text-align: center;
    margin-bottom: 40px;
  }

  .login-title {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 10px 0;
  }

  .login-subtitle {
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

  .btn-login {
    width: 100%;
    padding: 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 10px;
  }

  .btn-login:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
  }

  .register-link {
    text-align: center;
    margin-top: 25px;
    color: #6b7280;
    font-size: 15px;
  }

  .register-link a {
    color: #d97706;
    font-weight: 600;
    text-decoration: none;
  }

  .register-link a:hover {
    text-decoration: underline;
  }

  @media (max-width: 576px) {
    .login-container {
      padding: 40px 30px;
    }

    .login-title {
      font-size: 28px;
    }
  }
</style>

<div class="login-page">
  <div class="login-container">

    <div class="login-header">
      <h1 class="login-title">Login to Your Account</h1>
      <p class="login-subtitle">Welcome back! Please enter your details</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="error-message">
        ⚠️ <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

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
          placeholder="Enter your password"
          required>
      </div>

      <button type="submit" class="btn-login">
        Login
      </button>

    </form>

    <div class="register-link">
      Don't have an account? <a href="register.php">Register here</a>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>