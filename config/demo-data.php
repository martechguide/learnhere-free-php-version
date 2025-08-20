<?php
// Demo data functions for when database is not available

function getDemoBatches() {
    return [
        [
            'id' => 'demo-batch-1',
            'name' => 'Demo Medical Course',
            'description' => 'Complete medical training program with video lectures',
            'subject_count' => 5,
            'video_count' => 25,
            'image' => 'assets/images/medical-batch.jpg'
        ],
        [
            'id' => 'demo-batch-2', 
            'name' => 'Engineering Fundamentals',
            'description' => 'Core engineering concepts and practical applications',
            'subject_count' => 4,
            'video_count' => 20,
            'image' => 'assets/images/engineering-batch.jpg'
        ],
        [
            'id' => 'demo-batch-3',
            'name' => 'Computer Science Basics',
            'description' => 'Programming fundamentals and software development',
            'subject_count' => 6,
            'video_count' => 30,
            'image' => 'assets/images/cs-batch.jpg'
        ]
    ];
}

function getDemoSubjects($batch_id) {
    $subjects = [
        'demo-batch-1' => [
            ['id' => 'anatomy', 'name' => 'Human Anatomy', 'video_count' => 8],
            ['id' => 'physiology', 'name' => 'Physiology', 'video_count' => 6],
            ['id' => 'biochemistry', 'name' => 'Biochemistry', 'video_count' => 5],
            ['id' => 'pathology', 'name' => 'Pathology', 'video_count' => 4],
            ['id' => 'pharmacology', 'name' => 'Pharmacology', 'video_count' => 2]
        ],
        'demo-batch-2' => [
            ['id' => 'mechanics', 'name' => 'Engineering Mechanics', 'video_count' => 7],
            ['id' => 'thermodynamics', 'name' => 'Thermodynamics', 'video_count' => 5],
            ['id' => 'materials', 'name' => 'Materials Science', 'video_count' => 4],
            ['id' => 'electronics', 'name' => 'Electronics', 'video_count' => 4]
        ],
        'demo-batch-3' => [
            ['id' => 'programming', 'name' => 'Programming Fundamentals', 'video_count' => 8],
            ['id' => 'datastructures', 'name' => 'Data Structures', 'video_count' => 6],
            ['id' => 'algorithms', 'name' => 'Algorithms', 'video_count' => 5],
            ['id' => 'databases', 'name' => 'Database Systems', 'video_count' => 4],
            ['id' => 'networking', 'name' => 'Computer Networks', 'video_count' => 4],
            ['id' => 'webdev', 'name' => 'Web Development', 'video_count' => 3]
        ]
    ];
    
    return $subjects[$batch_id] ?? [];
}

function getDemoVideos($subject_id) {
    $videos = [
        'anatomy' => [
            ['id' => 'anatomy-1', 'title' => 'Introduction to Human Anatomy', 'duration' => '45:30', 'thumbnail' => 'assets/images/anatomy-1.jpg'],
            ['id' => 'anatomy-2', 'title' => 'Skeletal System Overview', 'duration' => '52:15', 'thumbnail' => 'assets/images/anatomy-2.jpg'],
            ['id' => 'anatomy-3', 'title' => 'Muscular System', 'duration' => '38:45', 'thumbnail' => 'assets/images/anatomy-3.jpg']
        ],
        'physiology' => [
            ['id' => 'physio-1', 'title' => 'Cell Physiology Basics', 'duration' => '41:20', 'thumbnail' => 'assets/images/physio-1.jpg'],
            ['id' => 'physio-2', 'title' => 'Cardiovascular System', 'duration' => '48:10', 'thumbnail' => 'assets/images/physio-2.jpg']
        ],
        'programming' => [
            ['id' => 'prog-1', 'title' => 'Introduction to Programming', 'duration' => '35:25', 'thumbnail' => 'assets/images/prog-1.jpg'],
            ['id' => 'prog-2', 'title' => 'Variables and Data Types', 'duration' => '28:40', 'thumbnail' => 'assets/images/prog-2.jpg'],
            ['id' => 'prog-3', 'title' => 'Control Structures', 'duration' => '42:15', 'thumbnail' => 'assets/images/prog-3.jpg']
        ]
    ];
    
    return $videos[$subject_id] ?? [];
}

function getDemoVideo($video_id) {
    $videos = [
        'anatomy-1' => [
            'id' => 'anatomy-1',
            'title' => 'Introduction to Human Anatomy',
            'description' => 'Learn the basics of human anatomy including body systems and organization.',
            'duration' => '45:30',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'thumbnail' => 'assets/images/anatomy-1.jpg'
        ],
        'prog-1' => [
            'id' => 'prog-1',
            'title' => 'Introduction to Programming',
            'description' => 'Basic concepts of programming and problem solving.',
            'duration' => '35:25',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'thumbnail' => 'assets/images/prog-1.jpg'
        ]
    ];
    
    return $videos[$video_id] ?? null;
}
?>
