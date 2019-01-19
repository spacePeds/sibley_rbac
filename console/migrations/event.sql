ALTER TABLE `sibley_rbac`.`event` 
CHANGE COLUMN `group` `group` ENUM('city', 'chamber', 'rec', 'hol') CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL ;
