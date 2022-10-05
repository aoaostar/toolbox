ALTER TABLE `toolbox_plugin` MODIFY COLUMN `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default' COMMENT '模板' AFTER `category_id`;

ALTER TABLE `toolbox_plugin` ADD COLUMN `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'visitor' COMMENT '权限' AFTER `template`;

UPDATE `toolbox_plugin` SET `permission` = 'visitor'