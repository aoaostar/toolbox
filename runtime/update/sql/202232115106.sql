SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `toolbox_plugin` ADD COLUMN `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default' AFTER `category_id`;

SET FOREIGN_KEY_CHECKS=1;