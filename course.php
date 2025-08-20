<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    header('Location: index.php');
    exit;
}

$course = getCourse($course_id);
if (!$course) {
    header('Location: index.php');
    exit;
}

$batch = getBatch($course['batch_id']);
$subjects = getSubjects($course_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['name']); ?> - LearnHereFree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="batch.php?id=<?php echo $course['batch_id']; ?>" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            <?php echo htmlspecialchars($batch['name']); ?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500"><?php echo htmlspecialchars($course['name']); ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <?php if (!empty($course['thumbnail_url'])): ?>
                        <img src="<?php echo htmlspecialchars($course['thumbnail_url']); ?>" 
                             alt="<?php echo htmlspecialchars($course['name']); ?>" 
                             class="w-16 h-16 rounded-lg object-cover">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-blue-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-book text-2xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($course['name']); ?></h1>
                    <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($course['description']); ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span><i class="fas fa-book mr-1"></i> <?php echo count($subjects); ?> subjects</span>
                        <span><i class="fas fa-video mr-1"></i> <?php echo getTotalVideosInCourse($course_id); ?> videos</span>
                        <span><i class="fas fa-clock mr-1"></i> Created <?php echo date('M j, Y', strtotime($course['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Subjects</h2>
            <div class="flex space-x-2">
                <button onclick="setViewMode('grid-medium')" 
                        class="px-3 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    <i class="fas fa-th mr-1"></i> Grid
                </button>
                <button onclick="setViewMode('list')" 
                        class="px-3 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <i class="fas fa-list mr-1"></i> List
                </button>
            </div>
        </div>

        <!-- Subjects Grid -->
        <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($subjects as $subject): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                 onclick="navigateToSubject('<?php echo $subject['id']; ?>')">
                <div class="relative">
                    <?php if (!empty($subject['thumbnail_url'])): ?>
                        <img src="<?php echo htmlspecialchars($subject['thumbnail_url']); ?>" 
                             alt="<?php echo htmlspecialchars($subject['name']); ?>" 
                             class="w-full h-48 object-cover rounded-t-lg">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-green-400 to-teal-600 rounded-t-lg flex items-center justify-center text-white">
                            <i class="fas fa-folder text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-2 right-2">
                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">
                            Subject
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($subject['name']); ?></h3>
                    <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($subject['description']); ?></p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span><i class="fas fa-video mr-1"></i> <?php echo count(getVideos($subject['id'])); ?> videos</span>
                        <span><i class="fas fa-clock mr-1"></i> <?php echo formatTotalDuration($subject['id']); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <?php if (empty($subjects)): ?>
        <div class="text-center py-12">
            <i class="fas fa-folder text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects available</h3>
            <p class="text-gray-500">This course doesn't have any subjects yet.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script>
        function navigateToSubject(subjectId) {
            window.location.href = `subject.php?id=${subjectId}`;
        }

        function setViewMode(mode) {
            const container = document.getElementById('subjects-container');
            if (mode === 'list') {
                container.className = 'grid grid-cols-1 gap-4';
            } else {
                container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
            }
        }
    </script>
</body>
</html>
