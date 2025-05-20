-- Add new columns to track replacement status
ALTER TABLE residence 
ADD COLUMN replacement_status ENUM('in_process', 'completed') DEFAULT NULL,
ADD COLUMN replacement_completed_date DATETIME DEFAULT NULL,
ADD COLUMN replacement_completed_by INT DEFAULT NULL; 