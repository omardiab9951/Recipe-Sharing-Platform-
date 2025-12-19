<?php
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Hashed Password Generator</h2>";
echo "<p><strong>Original Password:</strong> admin123</p>";
echo "<p><strong>Hashed Password:</strong></p>";
echo "<textarea style='width:100%; height:100px;'>" . $hashed . "</textarea>";
echo "<p>Copy the hashed password above and paste it in phpMyAdmin!</p>";
