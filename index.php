<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$batches = getBatches();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHereFree - Online Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to LearnHereFree</h1>
            <p class="text-xl text-gray-600 mb-8">Your gateway to free, high-quality education</p>
            <div class="flex justify-center space-x-4">
                <a href="#courses" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">
                    Start Learning
                </a>
                <a href="backend/admin.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-lg font-medium">
                    Admin Panel
                </a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo count($batches); ?></div>
                <div class="text-gray-600">Learning Batches</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    <?php 
                    $total_courses = 0;
                    foreach($batches as $batch) {
                        $total_courses += count(getCourses($batch['id']));
                    }
                    echo $total_courses;
                    ?>
                </div>
                <div class="text-gray-600">Total Courses</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    <?php 
                    $total_videos = 0;
                    foreach($batches as $batch) {
                        $total_videos += getTotalVideosInBatch($batch['id']);
                    }
                    echo $total_videos;
                    ?>
                </div>
                <div class="text-gray-600">Total Videos</div>
            </div>
        </div>

        <!-- Learning Batches Section -->
        <div id="courses" class="mb-8">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Learning Path</h2>
                    <p class="text-gray-600">Explore our comprehensive learning batches</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button id="grid-view" class="p-2 rounded-md bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="list-view" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php if (!empty($batches)): ?>
            <div id="batches-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($batches as $batch): ?>
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="navigateToBatch('<?php echo $batch['id']; ?>')">
                        <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-blue-400 to-purple-600 rounded-t-lg">
                            <?php if (!empty($batch['thumbnail_url'])): ?>
                                <img src="<?php echo htmlspecialchars($batch['thumbnail_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($batch['name']); ?>"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            <?php else: ?>
                                <div class="w-full h-48 flex items-center justify-center text-white">
                                    <i class="fas fa-graduation-cap text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($batch['name']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($batch['description'] ?? 'Complete learning program with video lectures'); ?></p>
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <span><i class="fas fa-book mr-1"></i> <?php echo count(getCourses($batch['id'])); ?> courses</span>
                                <span><i class="fas fa-video mr-1"></i> <?php echo getTotalVideosInBatch($batch['id']); ?> videos</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-sm border text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No batches available</h3>
                <p class="text-gray-600">Contact your administrator to get access to learning content.</p>
            </div>
        <?php endif; ?>

        <!-- Features Section -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-play text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Video Learning</h3>
                <p class="text-gray-600">High-quality video lectures from expert instructors</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Community</h3>
                <p class="text-gray-600">Learn together with a community of passionate learners</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-certificate text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Free Access</h3>
                <p class="text-gray-600">All courses and content are completely free to access</p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script>
        function navigateToBatch(batchId) {
            window.location.href = 'batch.php?id=' + batchId;
        }

        function setViewMode(mode) {
            const grid = document.getElementById('batches-grid');
            const gridBtn = document.getElementById('grid-view');
            const listBtn = document.getElementById('list-view');

            if (mode === 'grid') {
                grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
                gridBtn.className = 'p-2 rounded-md bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors';
                listBtn.className = 'p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors';
            } else {
                grid.className = 'grid grid-cols-1 gap-4';
                listBtn.className = 'p-2 rounded-md bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors';
                gridBtn.className = 'p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors';
            }
        }

        // Event listeners
        document.getElementById('grid-view').addEventListener('click', () => setViewMode('grid'));
        document.getElementById('list-view').addEventListener('click', () => setViewMode('list'));
    </script>
</body>
</html>