<?php

/**
 * Utility file to hash passwords for testing
 * Usage: Access via browser with ?password=your_password
 * Example: hash_password.php?password=123456
 */

if (isset($_GET['password'])) {
  $password = $_GET['password'];
  $hashed = password_hash($password, PASSWORD_DEFAULT);

  echo "<h1>Password Hash Generator</h1>";
  echo "<p><strong>Original Password:</strong> " . htmlspecialchars($password) . "</p>";
  echo "<p><strong>Hashed Password:</strong></p>";
  echo "<textarea style='width:100%; height:100px;'>" . htmlspecialchars($hashed) . "</textarea>";
} else {
  echo "<h1>Password Hash Generator</h1>";
  echo "<form method='GET'>";
  echo "<input type='text' name='password' placeholder='Enter password' required>";
  echo "<button type='submit'>Generate Hash</button>";
  echo "</form>";
}
