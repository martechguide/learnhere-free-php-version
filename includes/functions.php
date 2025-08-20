<?php
// Database connection and demo mode check
global $pdo;

// Check if we're in demo mode (no database connection)
$demo_mode = !isset($pdo) || $pdo === null;

function getBatches() {
    global $pdo;
    
    if (!$pdo) {
        // Demo data
        return [
            [
                'id' => 'demo-batch-1',
                'name' => 'Medical Science',
                'description' => 'Comprehensive medical education covering anatomy, physiology, and clinical practice',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=200&fit=crop',
                'is_active' => true,
                'created_at' => '2024-01-15 10:00:00'
            ],
            [
                'id' => 'demo-batch-2',
                'name' => 'Programming',
                'description' => 'Learn programming fundamentals and advanced concepts',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&h=200&fit=crop',
                'is_active' => true,
                'created_at' => '2024-01-15 11:00:00'
            ]
        ];
    }
    
    try {
        $stmt = $pdo->query("SELECT * FROM batches ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching batches: " . $e->getMessage());
        return [];
    }
}

function getBatch($id) {
    global $pdo;
    
    if (!$pdo) {
        $batches = getBatches();
        foreach ($batches as $batch) {
            if ($batch['id'] == $id) {
                return $batch;
            }
        }
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM batches WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching batch: " . $e->getMessage());
        return null;
    }
}

function getCourses($batch_id) {
    global $pdo;
    
    if (!$pdo) {
        // Demo data
        if ($batch_id === 'demo-batch-1') {
            return [
                [
                    'id' => 'demo-course-1',
                    'batch_id' => 'demo-batch-1',
                    'name' => 'Anatomy',
                    'description' => 'Study of the structure and organization of living things',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=400&h=200&fit=crop',
                    'order_index' => 0,
                    'is_active' => true,
                    'created_at' => '2024-01-15 10:30:00'
                ],
                [
                    'id' => 'demo-course-2',
                    'batch_id' => 'demo-batch-1',
                    'name' => 'Physiology',
                    'description' => 'Study of the functions and mechanisms in a living system',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=200&fit=crop',
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => '2024-01-15 10:45:00'
                ]
            ];
        }
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE batch_id = ? ORDER BY order_index ASC");
        $stmt->execute([$batch_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching courses: " . $e->getMessage());
        return [];
    }
}

function getCourse($id) {
    global $pdo;
    
    if (!$pdo) {
        $courses = getCourses('demo-batch-1');
        foreach ($courses as $course) {
            if ($course['id'] == $id) {
                return $course;
            }
        }
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching course: " . $e->getMessage());
        return null;
    }
}

function getSubjects($course_id) {
    global $pdo;
    
    if (!$pdo) {
        // Demo data
        if ($course_id === 'demo-course-1') {
            return [
                [
                    'id' => 'demo-subject-1',
                    'course_id' => 'demo-course-1',
                    'batch_id' => 'demo-batch-1',
                    'name' => 'Human Skeleton',
                    'description' => 'Introduction to the human skeletal system',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=400&h=200&fit=crop',
                    'order_index' => 0,
                    'created_at' => '2024-01-15 11:00:00'
                ],
                [
                    'id' => 'demo-subject-2',
                    'course_id' => 'demo-course-1',
                    'batch_id' => 'demo-batch-1',
                    'name' => 'Muscular System',
                    'description' => 'Study of muscles and movement',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=200&fit=crop',
                    'order_index' => 1,
                    'created_at' => '2024-01-15 11:15:00'
                ]
            ];
        }
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE course_id = ? ORDER BY order_index ASC");
        $stmt->execute([$course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching subjects: " . $e->getMessage());
        return [];
    }
}

function getSubject($id) {
    global $pdo;
    
    if (!$pdo) {
        $subjects = getSubjects('demo-course-1');
        foreach ($subjects as $subject) {
            if ($subject['id'] == $id) {
                return $subject;
            }
        }
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching subject: " . $e->getMessage());
        return null;
    }
}

function getVideos($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        // Demo data
        if ($subject_id === 'demo-subject-1') {
            return [
                [
                    'id' => 'demo-video-1',
                    'subject_id' => 'demo-subject-1',
                    'course_id' => 'demo-course-1',
                    'batch_id' => 'demo-batch-1',
                    'title' => 'Introduction to Human Skeleton',
                    'description' => 'Learn about the basic structure of the human skeleton',
                    'youtube_video_id' => 'dQw4w9WgXcQ',
                    'duration' => 212,
                    'order_index' => 0,
                    'is_active' => true,
                    'created_at' => '2024-01-15 12:00:00'
                ],
                [
                    'id' => 'demo-video-2',
                    'subject_id' => 'demo-subject-1',
                    'course_id' => 'demo-course-1',
                    'batch_id' => 'demo-batch-1',
                    'title' => 'Bone Structure and Types',
                    'description' => 'Understanding different types of bones and their structure',
                    'youtube_video_id' => 'dQw4w9WgXcQ',
                    'duration' => 385,
                    'order_index' => 1,
                    'is_active' => true,
                    'created_at' => '2024-01-15 12:15:00'
                ]
            ];
        }
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE subject_id = ? ORDER BY order_index ASC");
        $stmt->execute([$subject_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching videos: " . $e->getMessage());
        return [];
    }
}

function getVideo($id) {
    global $pdo;
    
    if (!$pdo) {
        $videos = getVideos('demo-subject-1');
        foreach ($videos as $video) {
            if ($video['id'] == $id) {
                return $video;
            }
        }
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching video: " . $e->getMessage());
        return null;
    }
}

function getPlatformVideos($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        // Demo data - empty for now
        return [];
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM multi_platform_videos WHERE subject_id = ? ORDER BY order_index ASC");
        $stmt->execute([$subject_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching platform videos: " . $e->getMessage());
        return [];
    }
}

function getAllPlatformVideos() {
    global $pdo;
    
    if (!$pdo) {
        // Demo data - empty for now
        return [];
    }
    
    try {
        $stmt = $pdo->query("SELECT * FROM multi_platform_videos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching all platform videos: " . $e->getMessage());
        return [];
    }
}

// Utility functions
function formatDuration($seconds) {
    if ($seconds < 60) {
        return $seconds . 's';
    } elseif ($seconds < 3600) {
        return floor($seconds / 60) . 'm ' . ($seconds % 60) . 's';
    } else {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return $hours . 'h ' . $minutes . 'm';
    }
}

function getTotalVideosInBatch($batch_id) {
    global $pdo;
    
    if (!$pdo) {
        return 6; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM videos WHERE batch_id = ?");
        $stmt->execute([$batch_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting videos in batch: " . $e->getMessage());
        return 0;
    }
}

function getTotalVideosInCourse($course_id) {
    global $pdo;
    
    if (!$pdo) {
        return 3; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM videos WHERE course_id = ?");
        $stmt->execute([$course_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting videos in course: " . $e->getMessage());
        return 0;
    }
}

function formatTotalDuration($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        return '15m 30s'; // Demo duration
    }
    
    try {
        $stmt = $pdo->prepare("SELECT SUM(duration) as total FROM videos WHERE subject_id = ?");
        $stmt->execute([$subject_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_seconds = $result['total'] ?? 0;
        return formatDuration($total_seconds);
    } catch (PDOException $e) {
        error_log("Error calculating total duration: " . $e->getMessage());
        return '0s';
    }
}

function formatTotalPlatformDuration($videos) {
    $total_seconds = 0;
    foreach ($videos as $video) {
        $total_seconds += $video['duration'] ?? 0;
    }
    return formatDuration($total_seconds);
}

// CRUD Functions for Admin Panel
function createBatch($name, $description, $thumbnail_url, $is_active = 1) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't create
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO batches (name, description, thumbnail_url, is_active, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $description, $thumbnail_url, $is_active]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating batch: " . $e->getMessage());
        return false;
    }
}

function createCourse($batch_id, $name, $description, $thumbnail_url, $order_index = 0, $is_active = 1) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't create
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO courses (batch_id, name, description, thumbnail_url, order_index, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$batch_id, $name, $description, $thumbnail_url, $order_index, $is_active]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating course: " . $e->getMessage());
        return false;
    }
}

function createSubject($course_id, $name, $description, $order_index = 0) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't create
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO subjects (course_id, name, description, order_index, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$course_id, $name, $description, $order_index]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating subject: " . $e->getMessage());
        return false;
    }
}

function createVideo($subject_id, $title, $description, $youtube_video_id, $duration, $order_index = 0) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't create
    }
    
    try {
        // Get course_id and batch_id from subject
        $subject = getSubject($subject_id);
        if (!$subject) return false;
        
        $stmt = $pdo->prepare("INSERT INTO videos (subject_id, course_id, batch_id, title, description, youtube_video_id, duration, order_index, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
        $stmt->execute([$subject_id, $subject['course_id'], $subject['batch_id'], $title, $description, $youtube_video_id, $duration, $order_index]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating video: " . $e->getMessage());
        return false;
    }
}

function deleteBatch($batch_id) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't delete
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM batches WHERE id = ?");
        $stmt->execute([$batch_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting batch: " . $e->getMessage());
        return false;
    }
}

function deleteCourse($course_id) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't delete
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting course: " . $e->getMessage());
        return false;
    }
}

function deleteSubject($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't delete
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->execute([$subject_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting subject: " . $e->getMessage());
        return false;
    }
}

function deleteVideo($video_id) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't delete
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->execute([$video_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting video: " . $e->getMessage());
        return false;
    }
}

function createPlatformVideo($subject_id, $title, $description, $platform, $video_url, $video_id, $duration, $order_index) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't create
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO multi_platform_videos (subject_id, title, description, platform, video_url, video_id, duration, order_index, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())");
        $stmt->execute([$subject_id, $title, $description, $platform, $video_url, $video_id, $duration, $order_index]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating platform video: " . $e->getMessage());
        return false;
    }
}

function deletePlatformVideo($video_id) {
    global $pdo;
    
    if (!$pdo) {
        return false; // Demo mode - can't delete
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM multi_platform_videos WHERE id = ?");
        $stmt->execute([$video_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error deleting platform video: " . $e->getMessage());
        return false;
    }
}

function getTotalDurationInSubject($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        return 0; // Demo mode
    }
    
    try {
        $stmt = $pdo->prepare("SELECT SUM(duration) as total FROM videos WHERE subject_id = ?");
        $stmt->execute([$subject_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error getting total duration: " . $e->getMessage());
        return 0;
    }
}

function getTotalCoursesInBatch($batch_id) {
    global $pdo;
    
    if (!$pdo) {
        return 2; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM courses WHERE batch_id = ?");
        $stmt->execute([$batch_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting courses in batch: " . $e->getMessage());
        return 0;
    }
}

function getTotalSubjectsInCourse($course_id) {
    global $pdo;
    
    if (!$pdo) {
        return 2; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM subjects WHERE course_id = ?");
        $stmt->execute([$course_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting subjects in course: " . $e->getMessage());
        return 0;
    }
}

function getTotalSubjectsInBatch($batch_id) {
    global $pdo;
    
    if (!$pdo) {
        return 4; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM subjects s JOIN courses c ON s.course_id = c.id WHERE c.batch_id = ?");
        $stmt->execute([$batch_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting subjects in batch: " . $e->getMessage());
        return 0;
    }
}

function getTotalVideosInSubject($subject_id) {
    global $pdo;
    
    if (!$pdo) {
        return 2; // Demo count
    }
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM videos WHERE subject_id = ?");
        $stmt->execute([$subject_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Error counting videos in subject: " . $e->getMessage());
        return 0;
    }
}

function getAllBatches() {
    return getBatches();
}
?>