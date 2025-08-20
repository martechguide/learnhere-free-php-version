-- Learn Here Free Database Schema
-- Complete hierarchical system: Batch → Course → Subject → Videos

-- Create database
CREATE DATABASE IF NOT EXISTS learnherefree CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE learnherefree;

-- Users table (for future authentication)
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(36) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Batches table (top level)
CREATE TABLE IF NOT EXISTS batches (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table (second level)
CREATE TABLE IF NOT EXISTS courses (
    id VARCHAR(36) PRIMARY KEY,
    batch_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail_url VARCHAR(500),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
);

-- Subjects table (third level)
CREATE TABLE IF NOT EXISTS subjects (
    id VARCHAR(36) PRIMARY KEY,
    batch_id VARCHAR(36) NOT NULL,
    course_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Videos table (fourth level - YouTube videos)
CREATE TABLE IF NOT EXISTS videos (
    id VARCHAR(36) PRIMARY KEY,
    subject_id VARCHAR(36) NOT NULL,
    course_id VARCHAR(36) NOT NULL,
    batch_id VARCHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    youtube_video_id VARCHAR(20) NOT NULL,
    duration INT DEFAULT 0,
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
);

-- Multi-platform videos table (fourth level - other platforms)
CREATE TABLE IF NOT EXISTS multi_platform_videos (
    id VARCHAR(36) PRIMARY KEY,
    subject_id VARCHAR(36),
    course_id VARCHAR(36),
    batch_id VARCHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    platform ENUM('facebook', 'vimeo', 'dailymotion', 'twitch', 'telegram', 'peertube', 'rumble') NOT NULL,
    video_id VARCHAR(100),
    video_url VARCHAR(500) NOT NULL,
    duration INT DEFAULT 0,
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
);

-- User progress tracking (optional)
CREATE TABLE IF NOT EXISTS user_progress (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    video_id VARCHAR(36) NOT NULL,
    progress_percentage DECIMAL(5,2) DEFAULT 0,
    watched_duration INT DEFAULT 0,
    is_completed BOOLEAN DEFAULT FALSE,
    last_watched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_video (user_id, video_id)
);

-- Create indexes for better performance
CREATE INDEX idx_batches_active ON batches(is_active);
CREATE INDEX idx_courses_batch_id ON courses(batch_id);
CREATE INDEX idx_courses_active ON courses(is_active);
CREATE INDEX idx_subjects_course_id ON subjects(course_id);
CREATE INDEX idx_subjects_batch_id ON subjects(batch_id);
CREATE INDEX idx_videos_subject_id ON videos(subject_id);
CREATE INDEX idx_videos_course_id ON videos(course_id);
CREATE INDEX idx_videos_batch_id ON videos(batch_id);
CREATE INDEX idx_videos_active ON videos(is_active);
CREATE INDEX idx_platform_videos_subject_id ON multi_platform_videos(subject_id);
CREATE INDEX idx_platform_videos_course_id ON multi_platform_videos(course_id);
CREATE INDEX idx_platform_videos_batch_id ON multi_platform_videos(batch_id);
CREATE INDEX idx_platform_videos_active ON multi_platform_videos(is_active);

-- Insert sample data
INSERT INTO batches (id, name, description, is_active) VALUES
('demo-batch-1', 'Demo Medical Course', 'Complete medical education with protected video content', TRUE),
('demo-batch-2', 'Engineering Fundamentals', 'Core engineering concepts and practical applications', TRUE),
('demo-batch-3', 'Computer Science Basics', 'Programming fundamentals and software development', TRUE);

INSERT INTO courses (id, batch_id, name, description, order_index, is_active) VALUES
('demo-course-1', 'demo-batch-1', 'Demo Course - Anatomy', 'Human anatomy fundamentals with video protection', 0, TRUE),
('demo-course-2', 'demo-batch-1', 'Demo Course - Physiology', 'Human physiology basics with protected content', 1, TRUE),
('demo-course-3', 'demo-batch-2', 'Mechanical Engineering', 'Core mechanical engineering principles', 0, TRUE),
('demo-course-4', 'demo-batch-3', 'Programming Basics', 'Introduction to programming concepts', 0, TRUE);

INSERT INTO subjects (id, batch_id, course_id, name, description, order_index) VALUES
('demo-subject-1', 'demo-batch-1', 'demo-course-1', 'Demo Subject - Basic Anatomy', 'Introduction to human anatomy with protected videos', 0),
('demo-subject-2', 'demo-batch-1', 'demo-course-1', 'Demo Subject - Advanced Anatomy', 'Advanced human anatomy concepts', 1),
('demo-subject-3', 'demo-batch-1', 'demo-course-2', 'Demo Subject - Basic Physiology', 'Introduction to human physiology', 0),
('demo-subject-4', 'demo-batch-2', 'demo-course-3', 'Mechanics Fundamentals', 'Basic mechanical engineering concepts', 0),
('demo-subject-5', 'demo-batch-3', 'demo-course-4', 'Programming Introduction', 'Basic programming concepts and syntax', 0);

INSERT INTO videos (id, subject_id, course_id, batch_id, title, description, youtube_video_id, duration, order_index, is_active) VALUES
('demo-video-1', 'demo-subject-1', 'demo-course-1', 'demo-batch-1', 'Demo Protected YouTube Video', 'This video demonstrates our YouTube protection system that hides branding and video IDs', 'dQw4w9WgXcQ', 212, 0, TRUE),
('demo-video-2', 'demo-subject-1', 'demo-course-1', 'demo-batch-1', 'Anatomy Basics Part 1', 'Introduction to human anatomy fundamentals', 'jNQXAC9IVRw', 180, 1, TRUE),
('demo-video-3', 'demo-subject-2', 'demo-course-1', 'demo-batch-1', 'Advanced Anatomy Concepts', 'Advanced human anatomy concepts and applications', 'kJQP7kiw5Fk', 240, 0, TRUE),
('demo-video-4', 'demo-subject-3', 'demo-course-2', 'demo-batch-1', 'Physiology Fundamentals', 'Basic human physiology concepts', '9bZkp7q19f0', 200, 0, TRUE),
('demo-video-5', 'demo-subject-4', 'demo-course-3', 'demo-batch-2', 'Mechanical Engineering Basics', 'Introduction to mechanical engineering principles', 'y6120QOlsfU', 300, 0, TRUE),
('demo-video-6', 'demo-subject-5', 'demo-course-4', 'demo-batch-3', 'Programming Introduction', 'Basic programming concepts and syntax', 'M7lc1UVf-VE', 150, 0, TRUE);

INSERT INTO multi_platform_videos (id, subject_id, course_id, batch_id, title, description, platform, video_url, duration, order_index, is_active) VALUES
('demo-platform-1', 'demo-subject-1', 'demo-course-1', 'demo-batch-1', 'Vimeo Anatomy Tutorial', 'Anatomy tutorial from Vimeo platform', 'vimeo', 'https://vimeo.com/123456789', 180, 1, TRUE),
('demo-platform-2', 'demo-subject-1', 'demo-course-1', 'demo-batch-1', 'Facebook Medical Lecture', 'Medical lecture from Facebook platform', 'facebook', 'https://facebook.com/watch?v=987654321', 240, 2, TRUE),
('demo-platform-3', 'demo-subject-4', 'demo-course-3', 'demo-batch-2', 'Dailymotion Engineering Video', 'Engineering concepts from Dailymotion', 'dailymotion', 'https://dailymotion.com/video/456789123', 300, 1, TRUE);

-- Insert demo admin user
INSERT INTO users (id, email, name, role, is_active) VALUES
('demo-admin-1', 'admin@learnherefree.com', 'Admin User', 'admin', TRUE);
