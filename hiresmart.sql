CREATE DATABASE IF NOT EXISTS hiresmart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hiresmart;

DROP TABLE IF EXISTS contacts;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('candidate', 'admin') NOT NULL DEFAULT 'candidate',
    phone VARCHAR(30) DEFAULT NULL,
    location VARCHAR(120) DEFAULT NULL,
    job_title VARCHAR(120) DEFAULT NULL,
    experience_years INT DEFAULT 0,
    skills TEXT DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    cv_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    company VARCHAR(150) NOT NULL,
    location VARCHAR(100) NOT NULL,
    level VARCHAR(50) NOT NULL,
    employment_type VARCHAR(50) NOT NULL DEFAULT 'Full-time',
    salary VARCHAR(100) DEFAULT NULL,
    description TEXT NOT NULL,
    responsibilities TEXT NOT NULL,
    requirements TEXT NOT NULL,
    skills TEXT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    cover_letter TEXT DEFAULT NULL,
    status ENUM('new', 'reviewed', 'interview', 'accepted', 'rejected') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_app_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_app_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (user_id, job_id)
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role, phone, location, job_title, experience_years, skills, bio)
VALUES
('HR Admin', 'admin@hiresmart.com', '$2y$10$0R3x5Wg6UqW5mzc6R8C4ie6l7T0ziLxaN4S2hgaD62m3b74k2sj3W', 'admin', '01000000000', 'القاهرة', 'HR Manager', 5, 'Recruitment, Interviews, Hiring', 'حساب إداري تجريبي');

-- Password: admin123

INSERT INTO jobs (title, company, location, level, employment_type, salary, description, responsibilities, requirements, skills)
VALUES
('Front-End Developer', 'PixelWorks', 'القاهرة', 'Mid', 'Full-time', '12,000 EGP', 'مطلوب مطور واجهات لديه أساس قوي في بناء صفحات احترافية سريعة ومتجاوبة.', 'تحويل التصميمات إلى صفحات عملية
التعاون مع فريق التصميم والـ Back-End
تحسين الأداء وتجربة المستخدم', 'إتقان HTML و CSS و JavaScript
خبرة جيدة في Responsive Design
خبرة React تعتبر ميزة إضافية', 'HTML, CSS, JavaScript, React'),
('UI/UX Designer', 'Nova Studio', 'عن بُعد', 'Junior', 'Full-time', '10,000 EGP', 'مطلوب مصمم واجهات وتجربة مستخدم لإنشاء تجارب رقمية واضحة وسهلة.', 'تصميم الشاشات والـ Wireframes
تحسين رحلة المستخدم
العمل مع فريق التطوير', 'معرفة Figma أو Adobe XD
فهم أساسيات UI و UX
Portfolio جيد', 'Figma, UX Research, Wireframing'),
('Data Analyst', 'DataCorp', 'الجيزة', 'Mid', 'Full-time', '11,000 EGP', 'مطلوب محلل بيانات لاستخراج الرؤى ودعم اتخاذ القرار.', 'تحليل البيانات
بناء تقارير ولوحات متابعة
التعامل مع قواعد البيانات', 'خبرة في Excel و SQL
قدرة على التحليل وعرض النتائج
أساسيات Power BI ميزة إضافية', 'Excel, SQL, Power BI'),
('Cyber Security Engineer', 'SecureNet', 'دبي', 'Senior', 'Full-time', '20,000 AED', 'مطلوب مهندس أمن سيبراني لحماية البنية التحتية ومتابعة الثغرات الأمنية.', 'مراقبة الأنظمة الأمنية
تنفيذ سياسات الحماية
تحليل التنبيهات والحوادث', 'خبرة قوية في Security Operations
فهم الشبكات وأنظمة الحماية
يفضل شهادات تخصصية', 'Networking, SOC, SIEM, Security');
