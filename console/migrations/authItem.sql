/*
-- Query: SELECT * FROM sibley_rbac.auth_item
LIMIT 0, 1000

-- Date: 2019-02-12 20:34
*/
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('chamberAdmin',1,'User who can administer Chamber related modules',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('cityAdmin',1,'User who can administer City related modules',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_alert',1,'Create a city alert',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_asset',1,'Ability to upload an image asset',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_business',1,'Create a business',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_category',1,'Create a category',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_event',1,'Create an event in the calendar',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_link',1,'Create a link',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_meeting',1,'Create a council meeting',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_minutes',1,'Create council meeting minutes',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_rec',1,'Create Rec Page',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_staff',1,'Create a city employee',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_subPage',1,'Allow a user to create a sub-page',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('create_user',1,'Create a new user',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_alert',1,'Delete a city alert',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_asset',1,'Ability to delete an image asset',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_business',1,'Delete a Business',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_category',1,'Delete a Category',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_event',1,'Delete an event in the calendar',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_link',1,'Delete a link',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_meeting',1,'Delete a council meeting',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_minutes',1,'Delete council meeting minutes',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_rec',1,'Delete Rec Page',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_staff',1,'Delete a city staff member',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('delete_subPage',1,'Delete a subPage',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('recAdmin',1,'User who can administer Rec Department related modules',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('superAdmin',1,'User who can administer all modules and create new users',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_alert',1,'Update a city alert',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_asset',1,'Ability to replace an image asset',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_business',1,'Update a business',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_category',1,'Update a Category',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_event',1,'Update an event in the Calendar',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_link',1,'Update a link',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_location',1,'Allow a user to update the location page',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_meeting',1,'Update a council meeting',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_minutes',1,'Update council meeting minutes',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_rec',1,'Update Rec Page',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_staff',1,'Allow a user to update city staff',NULL,NULL,NULL,NULL);
INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) VALUES ('update_subPage',1,'Update a subPage',NULL,NULL,NULL,NULL);
