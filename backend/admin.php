<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create_batch':
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $thumbnail_url = $_POST['thumbnail_url'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            createBatch($name, $description, $thumbnail_url, $is_active);
            break;
            
        case 'create_course':
            $batch_id = $_POST['batch_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $thumbnail_url = $_POST['thumbnail_url'] ?? '';
            $order_index = $_POST['order_index'] ?? 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            createCourse($batch_id, $name, $description, $thumbnail_url, $order_index, $is_active);
            break;
            
        case 'create_subject':
            $course_id = $_POST['course_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $order_index = $_POST['order_index'] ?? 0;
            createSubject($course_id, $name, $description, $order_index);
            break;
            
        case 'create_video':
            $subject_id = $_POST['subject_id'] ?? '';
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $youtube_video_id = $_POST['youtube_video_id'] ?? '';
            $duration = $_POST['duration'] ?? 0;
            $order_index = $_POST['order_index'] ?? 0;
            createVideo($subject_id, $title, $description, $youtube_video_id, $duration, $order_index);
            break;
            
        case 'create_platform_video':
            $subject_id = $_POST['subject_id'] ?? '';
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $platform = $_POST['platform'] ?? '';
            $video_url = $_POST['video_url'] ?? '';
            $video_id = $_POST['video_id'] ?? '';
            $duration = $_POST['duration'] ?? 0;
            $order_index = $_POST['order_index'] ?? 0;
            createPlatformVideo($subject_id, $title, $description, $platform, $video_url, $video_id, $duration, $order_index);
            break;
            
        case 'delete_batch':
            $batch_id = $_POST['batch_id'] ?? '';
            deleteBatch($batch_id);
            break;
            
        case 'delete_course':
            $course_id = $_POST['course_id'] ?? '';
            deleteCourse($course_id);
            break;
            
        case 'delete_subject':
            $subject_id = $_POST['subject_id'] ?? '';
            deleteSubject($subject_id);
            break;
            
        case 'delete_video':
            $video_id = $_POST['video_id'] ?? '';
            deleteVideo($video_id);
            break;
            
        case 'delete_platform_video':
            $video_id = $_POST['video_id'] ?? '';
            deletePlatformVideo($video_id);
            break;
    }
    
    // Redirect to prevent form resubmission
    $redirect_url = 'admin.php?tab=' . ($_POST['tab'] ?? 'batches');
    if (isset($_POST['batch_id'])) $redirect_url .= '&batch_id=' . $_POST['batch_id'];
    if (isset($_POST['course_id'])) $redirect_url .= '&course_id=' . $_POST['course_id'];
    if (isset($_POST['subject_id'])) $redirect_url .= '&subject_id=' . $_POST['subject_id'];
    header('Location: ' . $redirect_url);
    exit;
}

// Get current tab and selections
$active_tab = $_GET['tab'] ?? 'batches';
$selected_batch_id = $_GET['batch_id'] ?? null;
$selected_course_id = $_GET['course_id'] ?? null;
$selected_subject_id = $_GET['subject_id'] ?? null;

// Get data based on selections
$batches = getAllBatches();
$selected_batch = $selected_batch_id ? getBatch($selected_batch_id) : null;
$selected_course = $selected_course_id ? getCourse($selected_course_id) : null;
$selected_subject = $selected_subject_id ? getSubject($selected_subject_id) : null;

$courses = $selected_batch ? getCourses($selected_batch_id) : [];
$subjects = $selected_course ? getSubjects($selected_course_id) : [];
$videos = $selected_subject ? getVideos($selected_subject_id) : [];
$platform_videos = $selected_subject ? getPlatformVideos($selected_subject_id) : [];
$all_platform_videos = getAllPlatformVideos();

// Get statistics
$total_batches = count($batches);
$total_courses = array_sum(array_map(function($batch) { return getTotalCoursesInBatch($batch['id']); }, $batches));
$total_subjects = array_sum(array_map(function($course) { return getTotalSubjectsInCourse($course['id']); }, $courses));
$total_videos = array_sum(array_map(function($subject) { return getTotalVideosInSubject($subject['id']); }, $subjects));
$total_platform_videos = count($all_platform_videos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LearnHereFree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .tab-active {
            border-bottom: 2px solid #2563eb;
            color: #1f2937;
        }
        .tab-inactive {
            color: #6b7280;
        }
        .tab-inactive:hover {
            color: #2563eb;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">LearnHereFree</h1>
                            <p class="text-sm text-gray-600">Admin Dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Admin
                        </span>
                        <span class="text-sm text-gray-600">Admin User</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-full mx-auto py-6 px-2 sm:px-4 lg:px-8 overflow-hidden">
        <div class="bg-white shadow w-full max-w-full overflow-hidden rounded-lg">
            <!-- Tabs -->
            <div class="border-b border-gray-200 px-1 sm:px-2 lg:px-6 overflow-hidden">
                <div class="flex space-x-1 sm:space-x-2 lg:space-x-4 overflow-x-auto scrollbar-hide pb-1 w-full min-w-0">
                    <button 
                        class="tab-button flex items-center px-1 sm:px-2 lg:px-3 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap flex-shrink-0 min-w-0 transition-colors <?= $active_tab === 'batches' ? 'tab-active' : 'tab-inactive' ?>"
                        onclick="switchTab('batches')"
                    >
                        <i class="fas fa-users h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0"></i>
                        <span class="hidden md:inline">Content Management</span>
                        <span class="md:hidden">Content</span>
                    </button>
                    <button 
                        class="tab-button flex items-center px-1 sm:px-2 lg:px-3 py-3 sm:py-4 text-xs sm:text-sm whitespace-nowrap flex-shrink-0 min-w-0 transition-colors <?= $active_tab === 'multi-platform' ? 'tab-active' : 'tab-inactive' ?>"
                        onclick="switchTab('multi-platform')"
                    >
                        <i class="fas fa-video h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0"></i>
                        <span class="hidden md:inline">Multi-Platform Videos</span>
                        <span class="md:hidden">Videos</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6 mt-0">
                <!-- Content Management Tab -->
                <div id="batches-tab" class="tab-content <?= $active_tab === 'batches' ? '' : 'hidden' ?>">
                    <!-- Navigation Breadcrumbs -->
                    <div class="flex items-center gap-2 mb-6 text-sm text-gray-600">
                        <button 
                            class="hover:text-blue-600 transition-colors <?= !$selected_batch ? 'font-semibold text-gray-900' : '' ?>"
                            onclick="navigateTo('batches')"
                        >
                            Batches
                        </button>
                        <?php if ($selected_batch): ?>
                            <span>/</span>
                            <button 
                                class="hover:text-blue-600 transition-colors <?= !$selected_course ? 'font-semibold text-gray-900' : '' ?>"
                                onclick="navigateTo('batches', '<?= $selected_batch_id ?>')"
                            >
                                <?= htmlspecialchars($selected_batch['name']) ?>
                            </button>
                        <?php endif; ?>
                        <?php if ($selected_course): ?>
                            <span>/</span>
                            <button 
                                class="hover:text-blue-600 transition-colors <?= !$selected_subject ? 'font-semibold text-gray-900' : '' ?>"
                                onclick="navigateTo('batches', '<?= $selected_batch_id ?>', '<?= $selected_course_id ?>')"
                            >
                                <?= htmlspecialchars($selected_course['name']) ?>
                            </button>
                        <?php endif; ?>
                        <?php if ($selected_subject): ?>
                            <span>/</span>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($selected_subject['name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Content based on selection -->
                    <?php if (!$selected_batch): ?>
                        <!-- Batch list view -->
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Manage Batches</h3>
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                                    onclick="openModal('create-batch-modal')"
                                >
                                    <i class="fas fa-plus h-4 w-4 mr-2"></i>
                                    Create Batch
                                </button>
                            </div>
                            
                            <!-- Statistics Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Total Batches</p>
                                            <p class="text-2xl font-bold text-gray-900"><?= $total_batches ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-book text-green-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Total Courses</p>
                                            <p class="text-2xl font-bold text-gray-900"><?= $total_courses ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-folder text-yellow-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Total Subjects</p>
                                            <p class="text-2xl font-bold text-gray-900"><?= $total_subjects ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-play text-purple-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">Total Videos</p>
                                            <p class="text-2xl font-bold text-gray-900"><?= $total_videos ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Batches Grid -->
                            <div class="grid gap-4">
                                <?php if (empty($batches)): ?>
                                    <div class="text-center py-12">
                                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No batches yet</h3>
                                        <p class="text-gray-600 mb-4">Create your first batch to get started</p>
                                        <button 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                                            onclick="openModal('create-batch-modal')"
                                        >
                                            Create First Batch
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($batches as $batch): ?>
                                        <div class="border border-gray-200 rounded-lg p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                            <?= htmlspecialchars($batch['name']) ?>
                                                        </h4>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $batch['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                            <?= $batch['is_active'] ? 'Active' : 'Inactive' ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($batch['description']): ?>
                                                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($batch['description']) ?></p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-book mr-1"></i><?= getTotalCoursesInBatch($batch['id']) ?> courses</span>
                                                        <span><i class="fas fa-folder mr-1"></i><?= getTotalSubjectsInBatch($batch['id']) ?> subjects</span>
                                                        <span><i class="fas fa-play mr-1"></i><?= getTotalVideosInBatch($batch['id']) ?> videos</span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="navigateTo('batches', '<?= $batch['id'] ?>')"
                                                    >
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </button>
                                                    <button 
                                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="editBatch('<?= $batch['id'] ?>')"
                                                    >
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </button>
                                                    <button 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="deleteBatch('<?= $batch['id'] ?>')"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($selected_batch && !$selected_course): ?>
                        <!-- Course list view -->
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Manage Courses - <?= htmlspecialchars($selected_batch['name']) ?></h3>
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                                    onclick="openModal('create-course-modal')"
                                >
                                    <i class="fas fa-plus h-4 w-4 mr-2"></i>
                                    Create Course
                                </button>
                            </div>
                            
                            <!-- Courses Grid -->
                            <div class="grid gap-4">
                                <?php if (empty($courses)): ?>
                                    <div class="text-center py-12">
                                        <i class="fas fa-book text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h3>
                                        <p class="text-gray-600 mb-4">Create your first course in this batch</p>
                                        <button 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                                            onclick="openModal('create-course-modal')"
                                        >
                                            Create First Course
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($courses as $course): ?>
                                        <div class="border border-gray-200 rounded-lg p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                            <?= htmlspecialchars($course['name']) ?>
                                                        </h4>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $course['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                            <?= $course['is_active'] ? 'Active' : 'Inactive' ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($course['description']): ?>
                                                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($course['description']) ?></p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-folder mr-1"></i><?= getTotalSubjectsInCourse($course['id']) ?> subjects</span>
                                                        <span><i class="fas fa-play mr-1"></i><?= getTotalVideosInCourse($course['id']) ?> videos</span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="navigateTo('batches', '<?= $selected_batch_id ?>', '<?= $course['id'] ?>')"
                                                    >
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </button>
                                                    <button 
                                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="editCourse('<?= $course['id'] ?>')"
                                                    >
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </button>
                                                    <button 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="deleteCourse('<?= $course['id'] ?>')"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($selected_course && !$selected_subject): ?>
                        <!-- Subject list view -->
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Manage Subjects - <?= htmlspecialchars($selected_course['name']) ?></h3>
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                                    onclick="openModal('create-subject-modal')"
                                >
                                    <i class="fas fa-plus h-4 w-4 mr-2"></i>
                                    Create Subject
                                </button>
                            </div>
                            
                            <!-- Subjects Grid -->
                            <div class="grid gap-4">
                                <?php if (empty($subjects)): ?>
                                    <div class="text-center py-12">
                                        <i class="fas fa-folder text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects yet</h3>
                                        <p class="text-gray-600 mb-4">Create your first subject in this course</p>
                                        <button 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                                            onclick="openModal('create-subject-modal')"
                                        >
                                            Create First Subject
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($subjects as $subject): ?>
                                        <div class="border border-gray-200 rounded-lg p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                        <?= htmlspecialchars($subject['name']) ?>
                                                    </h4>
                                                    <?php if ($subject['description']): ?>
                                                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($subject['description']) ?></p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-play mr-1"></i><?= getTotalVideosInSubject($subject['id']) ?> videos</span>
                                                        <span><i class="fas fa-clock mr-1"></i><?= formatTotalDuration(getTotalDurationInSubject($subject['id'])) ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="navigateTo('batches', '<?= $selected_batch_id ?>', '<?= $selected_course_id ?>', '<?= $subject['id'] ?>')"
                                                    >
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </button>
                                                    <button 
                                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="editSubject('<?= $subject['id'] ?>')"
                                                    >
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </button>
                                                    <button 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="deleteSubject('<?= $subject['id'] ?>')"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($selected_subject): ?>
                        <!-- Video list view -->
                        <div>
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Manage Videos - <?= htmlspecialchars($selected_subject['name']) ?></h3>
                                <div class="flex space-x-2">
                                    <button 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                                        onclick="openModal('create-video-modal')"
                                    >
                                        <i class="fas fa-plus h-4 w-4 mr-2"></i>
                                        Add YouTube Video
                                    </button>
                                    <button 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                                        onclick="openModal('create-platform-video-modal')"
                                    >
                                        <i class="fas fa-plus h-4 w-4 mr-2"></i>
                                        Add Platform Video
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Videos Grid -->
                            <div class="grid gap-4">
                                <?php if (empty($videos) && empty($platform_videos)): ?>
                                    <div class="text-center py-12">
                                        <i class="fas fa-play text-gray-400 text-4xl mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No videos yet</h3>
                                        <p class="text-gray-600 mb-4">Add your first video to this subject</p>
                                        <div class="flex justify-center space-x-2">
                                            <button 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                                                onclick="openModal('create-video-modal')"
                                            >
                                                Add YouTube Video
                                            </button>
                                            <button 
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
                                                onclick="openModal('create-platform-video-modal')"
                                            >
                                                Add Platform Video
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($videos as $video): ?>
                                        <div class="border border-gray-200 rounded-lg p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                            <?= htmlspecialchars($video['title']) ?>
                                                        </h4>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fab fa-youtube mr-1"></i>YouTube
                                                        </span>
                                                    </div>
                                                    <?php if ($video['description']): ?>
                                                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($video['description']) ?></p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-clock mr-1"></i><?= formatDuration($video['duration']) ?></span>
                                                        <span><i class="fas fa-sort-numeric-up mr-1"></i>Order: <?= $video['order_index'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="deleteVideo('<?= $video['id'] ?>')"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php foreach ($platform_videos as $video): ?>
                                        <div class="border border-gray-200 rounded-lg p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                            <?= htmlspecialchars($video['title']) ?>
                                                        </h4>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fab fa-<?= strtolower($video['platform']) ?> mr-1"></i><?= htmlspecialchars($video['platform']) ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($video['description']): ?>
                                                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($video['description']) ?></p>
                                                    <?php endif; ?>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                        <span><i class="fas fa-clock mr-1"></i><?= formatDuration($video['duration']) ?></span>
                                                        <span><i class="fas fa-sort-numeric-up mr-1"></i>Order: <?= $video['order_index'] ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                        onclick="deletePlatformVideo('<?= $video['id'] ?>')"
                                                    >
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Multi-Platform Videos Tab -->
                <div id="multi-platform-tab" class="tab-content <?= $active_tab === 'multi-platform' ? '' : 'hidden' ?>">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Multi-Platform Videos</h3>
                        <button 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors"
                            onclick="openModal('create-platform-video-modal')"
                        >
                            <i class="fas fa-plus h-4 w-4 mr-2"></i>
                            Add Platform Video
                        </button>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-video text-blue-600 text-2xl mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Total Platform Videos</p>
                                <p class="text-2xl font-bold text-gray-900"><?= $total_platform_videos ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Platform Videos Grid -->
                    <div class="grid gap-4">
                        <?php if (empty($all_platform_videos)): ?>
                            <div class="text-center py-12">
                                <i class="fas fa-video text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No platform videos yet</h3>
                                <p class="text-gray-600 mb-4">Add your first multi-platform video</p>
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                                    onclick="openModal('create-platform-video-modal')"
                                >
                                    Add First Video
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($all_platform_videos as $video): ?>
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                    <?= htmlspecialchars($video['title']) ?>
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fab fa-<?= strtolower($video['platform']) ?> mr-1"></i><?= htmlspecialchars($video['platform']) ?>
                                                </span>
                                            </div>
                                            <?php if ($video['description']): ?>
                                                <p class="text-gray-600 mb-4"><?= htmlspecialchars($video['description']) ?></p>
                                            <?php endif; ?>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span><i class="fas fa-clock mr-1"></i><?= formatDuration($video['duration']) ?></span>
                                                <span><i class="fas fa-sort-numeric-up mr-1"></i>Order: <?= $video['order_index'] ?></span>
                                                <?php if ($video['subject_id']): ?>
                                                    <span><i class="fas fa-folder mr-1"></i>Subject: <?= htmlspecialchars(getSubject($video['subject_id'])['name'] ?? 'Unknown') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button 
                                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                onclick="editPlatformVideo('<?= $video['id'] ?>')"
                                            >
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <button 
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                                onclick="deletePlatformVideo('<?= $video['id'] ?>')"
                                            >
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Tab switching
        function switchTab(tab) {
            // Update URL
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            document.getElementById(tab + '-tab').classList.remove('hidden');
            
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('tab-active');
                button.classList.add('tab-inactive');
            });
            
            event.target.classList.remove('tab-inactive');
            event.target.classList.add('tab-active');
        }
        
        // Navigation
        function navigateTo(tab, batchId = null, courseId = null, subjectId = null) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            if (batchId) url.searchParams.set('batch_id', batchId);
            if (courseId) url.searchParams.set('course_id', courseId);
            if (subjectId) url.searchParams.set('subject_id', subjectId);
            window.location.href = url.toString();
        }
        
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        
        // Delete functions
        function deleteBatch(batchId) {
            if (confirm('Are you sure you want to delete this batch? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_batch">
                    <input type="hidden" name="batch_id" value="${batchId}">
                    <input type="hidden" name="tab" value="batches">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course? This will also delete all subjects and videos in this course.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_course">
                    <input type="hidden" name="course_id" value="${courseId}">
                    <input type="hidden" name="tab" value="batches">
                    <input type="hidden" name="batch_id" value="<?= $selected_batch_id ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteSubject(subjectId) {
            if (confirm('Are you sure you want to delete this subject? This will also delete all videos in this subject.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_subject">
                    <input type="hidden" name="subject_id" value="${subjectId}">
                    <input type="hidden" name="tab" value="batches">
                    <input type="hidden" name="batch_id" value="<?= $selected_batch_id ?>">
                    <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteVideo(videoId) {
            if (confirm('Are you sure you want to delete this video?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_video">
                    <input type="hidden" name="video_id" value="${videoId}">
                    <input type="hidden" name="tab" value="batches">
                    <input type="hidden" name="batch_id" value="<?= $selected_batch_id ?>">
                    <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">
                    <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deletePlatformVideo(videoId) {
            if (confirm('Are you sure you want to delete this platform video?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_platform_video">
                    <input type="hidden" name="video_id" value="${videoId}">
                    <input type="hidden" name="tab" value="<?= $active_tab ?>">
                    <input type="hidden" name="batch_id" value="<?= $selected_batch_id ?>">
                    <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">
                    <input type="hidden" name="subject_id" value="<?= $selected_subject_id ?>">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Edit functions (placeholder for now)
        function editBatch(batchId) {
            alert('Edit functionality will be implemented soon!');
        }
        
        function editCourse(courseId) {
            alert('Edit functionality will be implemented soon!');
        }
        
        function editSubject(subjectId) {
            alert('Edit functionality will be implemented soon!');
        }
        
        function editPlatformVideo(videoId) {
            alert('Edit functionality will be implemented soon!');
        }
    </script>
</body>
</html>
