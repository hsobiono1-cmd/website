-- voting_system3.sql
-- Database:    

CREATE DATABASE IF NOT EXISTS voting_system3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voting_system3;

-- admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- voters table
CREATE TABLE IF NOT EXISTS voters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    address TEXT NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    has_voted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- positions table
CREATE TABLE IF NOT EXISTS positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- candidates table
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    photo VARCHAR(255) DEFAULT 'default.png',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
);

-- votes table
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL,
    position_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
);

-- election_status table
CREATE TABLE IF NOT EXISTS election_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('inactive','active','ended') DEFAULT 'inactive',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default election status
INSERT INTO election_status (status) VALUES ('inactive');

-- Insert default admin (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert default positions
INSERT INTO positions (position_name) VALUES 
('President'),
('Vice President'),
('Secretary'),
('Treasurer'),
('Auditor'),
('Public Information Officer');


INSERT INTO `candidates`
(`position_id`, `first_name`, `last_name`, `photo`, `description`, `created_at`)
VALUES
(1, 'BongBong', 'Marcos', 'cand_1783417620_558.jpg', 'Good governance begins with responsible citizens.', NOW()),
(1, 'Rodrigo', 'Duterte', 'cand_1783417708_825.jpg', 'Together, we build a stronger nation.', NOW()),
(2, 'Leni', 'Robredo', 'cand_1783417789_936.jpg', 'Transparency today, trust tomorrow.', NOW()),
(2, 'Robin', 'Padilla', 'cand_1783417825_217.jpg', 'Your voice matters. Your vote shapes the future.', NOW()),
(3, 'Kiko', 'Pangilinan', 'cand_1783417955_419.jpg', 'Serve with integrity. Lead with purpose.', NOW()),
(3, 'Tito', 'Sotto', 'cand_1783418182_514.jpg', 'A united community creates lasting progress.', NOW()),
(5, 'Bato', 'Dela Rosa', 'cand_1783418221_916.jpg', 'Every citizen has the power to make a difference.', NOW()),
(5, 'Bam', 'Aquino', 'cand_1783418292_362.jpg', 'Honesty in leadership inspires confidence in the people.', NOW()),
(4, 'Pablo', 'Escobar', 'cand_1783418448_588.jpg', 'Stronger communities start with active participation.', NOW()),
(4, 'Al', 'Pacino', 'cand_1783418537_120.jpg', 'Progress is achieved through cooperation and accountability.', NOW());
