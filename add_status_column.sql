-- Add status column to tasheel_transactions table if it doesn't exist
USE snjst;

-- Check if the status column already exists
SET @columnExists = 0;
SELECT COUNT(*) INTO @columnExists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'snjst' 
AND TABLE_NAME = 'tasheel_transactions' 
AND COLUMN_NAME = 'status';

-- Add the column if it doesn't exist
SET @query = IF(@columnExists = 0, 
    'ALTER TABLE tasheel_transactions ADD COLUMN status VARCHAR(20) DEFAULT "in_process"',
    'SELECT "Status column already exists."');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update existing records to have 'in_process' status if NULL
UPDATE tasheel_transactions SET status = 'in_process' WHERE status IS NULL; 