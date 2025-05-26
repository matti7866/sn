-- SQL script to create the residence_replace_log table
CREATE TABLE IF NOT EXISTS `residence_replace_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_residence_id` int(11) NOT NULL,
  `new_residence_id` int(11) NOT NULL,
  `replace_date` datetime NOT NULL,
  `replaced_by` int(11) NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `original_residence_id` (`original_residence_id`),
  KEY `new_residence_id` (`new_residence_id`),
  KEY `replaced_by` (`replaced_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraints
ALTER TABLE `residence_replace_log`
  ADD CONSTRAINT `residence_replace_log_ibfk_1` FOREIGN KEY (`original_residence_id`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE,
  ADD CONSTRAINT `residence_replace_log_ibfk_2` FOREIGN KEY (`new_residence_id`) REFERENCES `residence` (`residenceID`) ON DELETE CASCADE,
  ADD CONSTRAINT `residence_replace_log_ibfk_3` FOREIGN KEY (`replaced_by`) REFERENCES `staff` (`staff_id`); 