SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `toolbox_plugin` DROP COLUMN `logo`;

ALTER TABLE `toolbox_user` ADD COLUMN `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像' AFTER `stars`;

ALTER TABLE `toolbox_user` ADD COLUMN `oauth` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'oauth信息json' AFTER `update_time`;

ALTER TABLE `toolbox_user` ADD COLUMN `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ip' AFTER `oauth`;

ALTER TABLE `toolbox_user` MODIFY COLUMN `stars` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标星json array' AFTER `username`;

ALTER TABLE `toolbox_user` DROP COLUMN `avatar_url`;

ALTER TABLE `toolbox_user` ADD UNIQUE INDEX `username`(`username`) USING BTREE;

UPDATE `toolbox_user` SET `oauth` = JSON_OBJECT('github', id)

SET FOREIGN_KEY_CHECKS=1;