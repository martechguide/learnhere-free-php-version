<?php
session_start();

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Handle form submissions
if ($_POST) {
    switch ($step) {
        case 2:
            // Database connection test
            $host = $_POST['db_host'];
            $name = $_POST['db_name'];
            $user = $_POST['db_user'];
            $pass = $_POST['db_pass'];
            
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Test if tables exist
                $stmt = $pdo->query("SHOW TABLES LIKE 'batches'");
                if ($stmt->rowCount() == 0) {
                    // Import schema
                    $schema = file_get_contents('database/schema.sql');
                    $pdo->exec($schema);
                    $success = "Database setup completed successfully!";
                } else {
                    $success = "Database already configured!";
                }
                
                // Create config file
                $config = "<?php
// Database configuration
\$db_host = '$host';
\$db_name = '$name';
\$db_user = '$user';
\$db_pass = '$pass';

// You can also use environment variables for production
if (getenv('DB_HOST')) {
    \$db_host = getenv('DB_HOST');
    \$db_name = getenv('DB_NAME');
    \$db_user = getenv('DB_USER');
    \$db_pass = getenv('DB_PASS');
}

try {
    \$pdo = new PDO(\"mysql:host=\$db_host;dbname=\$db_name;charset=utf8mb4\", \$db_user, \$db_pass);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException \$e) {
    \$pdo = null;
    error_log(\"Database connection failed: \" . \$e->getMessage());
}

// Include demo data functions
require_once 'demo-data.php';
?>";
                
                file_put_contents('config/database.php', $config);
                $step = 3;
                
            } catch (Exception $e) {
                $error = "Database connection failed: " . $e->getMessage();
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Here Free - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Learn Here Free</h1>
                <p class="text-gray-600">Installation Wizard</p>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Step <?php echo $step; ?> of 3</span>
                    <span class="text-sm text-gray-600"><?php echo round(($step/3)*100); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo ($step/3)*100; ?>%"></div>
                </div>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($step == 1): ?>
                <!-- Welcome Step -->
                <div class="text-center">
                    <i class="fas fa-graduation-cap text-4xl text-blue-600 mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Welcome to Learn Here Free</h2>
                    <p class="text-gray-600 mb-6">
                        This installation wizard will help you set up your learning management system.
                        Make sure you have your database credentials ready.
                    </p>
                    <a href="?step=2" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Start Installation
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
            <?php elseif ($step == 2): ?>
                <!-- Database Configuration -->
                <form method="POST" action="?step=2">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Database Configuration</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                            <input type="text" name="db_host" value="<?php echo $_POST['db_host'] ?? 'localhost'; ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                            <input type="text" name="db_name" value="<?php echo $_POST['db_name'] ?? 'learnherefree'; ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Username</label>
                            <input type="text" name="db_user" value="<?php echo $_POST['db_user'] ?? 'root'; ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Password</label>
                            <input type="password" name="db_pass" value="<?php echo $_POST['db_pass'] ?? ''; ?>" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="?step=1" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Back
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Test Connection & Install
                        </button>
                    </div>
                </form>
                
            <?php elseif ($step == 3): ?>
                <!-- Installation Complete -->
                <div class="text-center">
                    <i class="fas fa-check-circle text-4xl text-green-600 mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Installation Complete!</h2>
                    <p class="text-gray-600 mb-6">
                        Your Learn Here Free installation has been completed successfully.
                        You can now start using your learning management system.
                    </p>
                    
                    <div class="space-y-3">
                        <a href="index.php" class="block w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            <i class="fas fa-home mr-2"></i>
                            Go to Homepage
                        </a>
                        <a href="index.php?page=admin" class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="fas fa-cog mr-2"></i>
                            Access Admin Panel
                        </a>
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Security Note:</strong> Please delete the <code>install.php</code> file after installation for security.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
