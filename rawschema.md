1- CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','owner','editor','readonly') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

2- CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

3- CREATE TABLE exam_forms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT (nullable),
    category_id BIGINT UNSIGNED (Nullable),
    exam_type ENUM('single_choice','multi_choice','text_input') NOT NULL,
   user_id BIGINT UNSIGNED NOT NULL, -- who created it
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
4- CREATE TABLE exam_exclusions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    participant_id bigint (255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE
);

5- CREATE TABLE exam_questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    marks DECIMAL(5,2) NOT NULL DEFAULT 1,
    user_id BIGINT UNSIGNED NOT NULL, -- the onwer to the exam
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

6- CREATE TABLE exam_question_options (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id BIGINT UNSIGNED NOT NULL,
    exam_id BIGINT UNSIGNED NOT NULL,
    option_text text NOT NULL,-- this option is the answers to choice from
    correct_option int NOT NULL , -- it will store the correct option
    user_id BIGINT UNSIGNED NOT NULL, --owner of the exam
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);



7- CREATE TABLE participants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    participant_id VARCHAR(255) NOT NULL, -- university/school ID
    fullname VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_exam_participant (exam_id, participant_id),
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE
);

-- this table will store the answer written , which means if exam has direct question it should store the answers written by the anonmous participant
8- CREATE TABLE participant_exam_direct_question_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    participant_id VARCHAR(255) NOT NULL,
    text_answer TEXT NULL,         -- written answer for text_input questions
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE
);

-- this table stores the options answer for single or multipl choice question by pariticipant
9- CREATE TABLE participant_option_answers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    participant_id VARCHAR(255) NOT NULL,
    selected_option JSON, -- for single or multi-choice answers
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exam_forms(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE
);





