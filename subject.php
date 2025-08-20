<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$subject_id = $_GET['id'] ?? null;
if (!$subject_id) {
    header('Location: index.php');
    exit;
}

$subject = getSubject($subject_id);
if (!$subject) {
    header('Location: index.php');
    exit;
}

$course = getCourse($subject['course_id']);
$batch = getBatch($subject['batch_id']);
$videos = getVideos($subject_id);
$platform_videos = getPlatformVideos($subject_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject['name']); ?> - LearnHereFree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/video-protection.css">
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
                        <a href="batch.php?id=<?php echo $subject['batch_id']; ?>" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            <?php echo htmlspecialchars($batch['name']); ?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="course.php?id=<?php echo $subject['course_id']; ?>" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            <?php echo htmlspecialchars($course['name']); ?>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500"><?php echo htmlspecialchars($subject['name']); ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Subject Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <?php if (!empty($subject['thumbnail_url'])): ?>
                        <img src="<?php echo htmlspecialchars($subject['thumbnail_url']); ?>" 
                             alt="<?php echo htmlspecialchars($subject['name']); ?>" 
                             class="w-16 h-16 rounded-lg object-cover">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-teal-600 rounded-lg flex items-center justify-center text-white">
                            <i class="fas fa-folder text-2xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($subject['name']); ?></h1>
                    <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($subject['description']); ?></p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span><i class="fas fa-video mr-1"></i> <?php echo count($videos); ?> videos</span>
                        <span><i class="fas fa-clock mr-1"></i> <?php echo formatTotalDuration($subject_id); ?></span>
                        <span><i class="fas fa-calendar mr-1"></i> Created <?php echo date('M j, Y', strtotime($subject['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Player Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Video Player -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div id="video-player" class="video-protection-container aspect-w-16 aspect-h-9 bg-gray-900 rounded-lg mb-4 relative">
                        <div class="flex items-center justify-center h-96">
                            <div class="text-center text-white">
                                <i class="fas fa-play-circle text-6xl mb-4"></i>
                                <p class="text-lg">Select a video to start watching</p>
                            </div>
                        </div>
                    </div>
                    <div id="video-info" class="hidden">
                        <h2 id="video-title" class="text-xl font-semibold text-gray-900 mb-2"></h2>
                        <p id="video-description" class="text-gray-600 mb-4"></p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span id="video-duration"><i class="fas fa-clock mr-1"></i></span>
                            <span id="video-platform"><i class="fas fa-play mr-1"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Videos</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <?php if (!empty($videos)): ?>
                            <?php foreach ($videos as $index => $video): ?>
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                 onclick="playVideo('<?php echo $video['id']; ?>', '<?php echo htmlspecialchars($video['title']); ?>', '<?php echo htmlspecialchars($video['description']); ?>', '<?php echo $video['youtube_video_id']; ?>', '<?php echo formatDuration($video['duration']); ?>', 'YouTube')">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <img src="https://img.youtube.com/vi/<?php echo $video['youtube_video_id']; ?>/mqdefault.jpg" 
                                             alt="<?php echo htmlspecialchars($video['title']); ?>" 
                                             class="w-16 h-12 object-cover rounded">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($video['title']); ?></h4>
                                        <p class="text-xs text-gray-500"><?php echo formatDuration($video['duration']); ?></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">YT</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($platform_videos)): ?>
                            <?php foreach ($platform_videos as $video): ?>
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                 onclick="playVideo('<?php echo $video['id']; ?>', '<?php echo htmlspecialchars($video['title']); ?>', '<?php echo htmlspecialchars($video['description']); ?>', '<?php echo $video['video_url']; ?>', '<?php echo formatDuration($video['duration']); ?>', '<?php echo htmlspecialchars($video['platform']); ?>')">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-video text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($video['title']); ?></h4>
                                        <p class="text-xs text-gray-500"><?php echo formatDuration($video['duration']); ?></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded"><?php echo htmlspecialchars($video['platform']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (empty($videos) && empty($platform_videos)): ?>
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-video text-2xl mb-2"></i>
                            <p>No videos available</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/video-protection.js"></script>
    <script>
        function playVideo(videoId, title, description, videoUrl, duration, platform) {
            const videoPlayer = document.getElementById('video-player');
            const videoInfo = document.getElementById('video-info');
            const videoTitle = document.getElementById('video-title');
            const videoDescription = document.getElementById('video-description');
            const videoDuration = document.getElementById('video-duration');
            const videoPlatform = document.getElementById('video-platform');

            // Update video info
            videoTitle.textContent = title;
            videoDescription.textContent = description;
            videoDuration.innerHTML = `<i class="fas fa-clock mr-1"></i>${duration}`;
            videoPlatform.innerHTML = `<i class="fas fa-play mr-1"></i>${platform}`;

            // Show video info
            videoInfo.classList.remove('hidden');

            // Update video player with protection
            if (platform === 'YouTube') {
                // Create protected YouTube embed
                videoPlayer.innerHTML = `
                    <iframe src="https://www.youtube.com/embed/${videoUrl}?autoplay=1&rel=0&modestbranding=1&showinfo=0" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen 
                            class="w-full h-96 rounded-lg"
                            title="${title}">
                    </iframe>
                `;
                
                // Initialize YouTube protection after iframe loads
                const iframe = videoPlayer.querySelector('iframe');
                iframe.onload = function() {
                    // Add protection overlays
                    addProtectionOverlays(videoPlayer, 'youtube');
                    addProtectionEventListeners(videoPlayer);
                };
            } else if (platform === 'Facebook') {
                // Create protected Facebook embed
                videoPlayer.className = 'facebook-protection-container aspect-w-16 aspect-h-9 bg-gray-900 rounded-lg mb-4 relative';
                videoPlayer.innerHTML = `
                    <iframe src="https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(videoUrl)}&show_text=0&width=560" 
                            frameborder="0" 
                            allowfullscreen="true" 
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                            class="w-full h-96 rounded-lg"
                            title="${title}">
                    </iframe>
                `;
                
                // Initialize Facebook protection after iframe loads
                const iframe = videoPlayer.querySelector('iframe');
                iframe.onload = function() {
                    // Add protection overlays
                    addProtectionOverlays(videoPlayer, 'facebook');
                    addProtectionEventListeners(videoPlayer);
                };
            } else {
                // Create protected platform video
                videoPlayer.className = 'platform-protection-container aspect-w-16 aspect-h-9 bg-gray-900 rounded-lg mb-4 relative';
                
                if (videoUrl.includes('.mp4') || videoUrl.includes('.webm') || videoUrl.includes('.ogg')) {
                    videoPlayer.innerHTML = `
                        <video controls class="w-full h-96 rounded-lg">
                            <source src="${videoUrl}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    `;
                } else {
                    // Generic iframe for other platforms
                    videoPlayer.innerHTML = `
                        <iframe src="${videoUrl}" 
                                frameborder="0" 
                                allowfullscreen 
                                class="w-full h-96 rounded-lg"
                                title="${title}">
                        </iframe>
                    `;
                }
                
                // Initialize platform protection
                setTimeout(function() {
                    addProtectionOverlays(videoPlayer, 'platform');
                    addProtectionEventListeners(videoPlayer);
                }, 500);
            }
        }
    </script>
</body>
</html>
