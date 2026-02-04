<?php
// Test admin login
$users = [
    ['username' => 'admin', 'password' => 'admin123', 'hash' => '$2y$12$sOCBKOvT0PrGXqHIHO2VtODuzx/l3Ccc0/bWnz5RptAtNjCrl/z92']
];

echo "Testing password verification:\n";

foreach ($users as $user) {
    $valid = password_verify($user['password'], $user['hash']);
    echo "Username: {$user['username']}\n";
    echo "Password: {$user['password']}\n";
    echo "Hash: {$user['hash']}\n";
    echo "Valid: " . ($valid ? 'YES' : 'NO') . "\n\n";
}

// Test if you can verify
echo "Test password_verify:\n";
$test = password_verify('admin123', '$2y$12$sOCBKOvT0PrGXqHIHO2VtODuzx/l3Ccc0/bWnz5RptAtNjCrl/z92');
echo $test ? "✅ Password 'admin123' matches the hash\n" : "❌ Password doesn't match\n";

// Generate a new hash
$new_hash = password_hash('admin123', PASSWORD_BCRYPT);
echo "\nNew hash for 'admin123': $new_hash\n";
?>