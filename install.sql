SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for toolbox_category
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_category`;
CREATE TABLE `toolbox_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT '权重',
  `create_time` datetime NOT NULL COMMENT '安装时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_category
-- ----------------------------

-- ----------------------------
-- Table structure for toolbox_config
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_config`;
CREATE TABLE `toolbox_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标题',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '值',
  `create_time` datetime NOT NULL COMMENT '安装时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_config
-- ----------------------------
INSERT INTO `toolbox_config` VALUES (1, 'global.title', '傲星工具箱', '2021-12-22 18:31:59', '2022-09-05 16:28:49');
INSERT INTO `toolbox_config` VALUES (2, 'global.subtitle', '一个非常Nice的在线工具箱1', '2021-12-22 18:31:59', '2022-09-09 09:55:16');
INSERT INTO `toolbox_config` VALUES (3, 'global.keywords', '傲星工具箱,在线工具箱,aoaostar,pluto', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (4, 'global.description', '这是一个非常Nice的在线工具箱', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (5, 'global.template', 'default', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (6, 'global.foot_code', '\n            <div style=\"display: none\">\n                <script type=\"text/javascript\" src=\"https://s9.cnzz.com/z_stat.php?id=1280727911&web_id=1280727911\"></script>\n            </div>', '2021-12-22 21:34:41', '2022-09-05 15:50:47');
INSERT INTO `toolbox_config` VALUES (7, 'global.admin_path', 'admin', '2021-12-22 18:31:59', '2022-09-05 16:38:45');
INSERT INTO `toolbox_config` VALUES (8, 'global.secret_key', '8T7SHDiZFEY443f4GHicAmtxFsZw6FEi', '2022-09-03 13:42:20', '2022-09-09 09:55:09');
INSERT INTO `toolbox_config` VALUES (9, 'cdn.cdnjs', 'https://cdn.staticfile.org', '2022-03-02 17:56:15', '2022-03-02 17:56:15');
INSERT INTO `toolbox_config` VALUES (10, 'cdn.npm', 'https://npm.elemecdn.com', '2022-03-02 17:56:15', '2022-03-02 18:13:43');
INSERT INTO `toolbox_config` VALUES (11, 'cloud.mirror', 'https://github.com/{owner}/{repo}/raw/{branch}/{path}', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (12, 'oauth.github.enable', '1', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (13, 'oauth.github.client_id', '', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (14, 'oauth.gitee.client_secret', '', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (15, 'oauth.gitee.enable', '0', '2021-12-22 18:31:59', '2022-09-09 22:28:34');
INSERT INTO `toolbox_config` VALUES (16, 'oauth.gitee.client_id', '', '2022-09-09 11:37:42', '2022-09-09 11:37:48');
INSERT INTO `toolbox_config` VALUES (17, 'oauth.github.client_secret', '', '2021-12-22 18:31:59', '2021-12-22 18:31:59');

-- ----------------------------
-- Table structure for toolbox_migration
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_migration`;
CREATE TABLE `toolbox_migration`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `filename`(`filename`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_migration
-- ----------------------------
INSERT INTO `toolbox_migration` VALUES (1, '202203021857.sql', '2022-09-11 14:22:55');
INSERT INTO `toolbox_migration` VALUES (2, '202203211510.sql', '2022-09-11 14:23:03');
INSERT INTO `toolbox_migration` VALUES (3, '202209111427.sql', '2022-09-11 14:30:19');

-- ----------------------------
-- Table structure for toolbox_plugin
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_plugin`;
CREATE TABLE `toolbox_plugin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '插件标题',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '插件描述',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '插件名',
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '插件类',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置信息',
  `version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'v1.0' COMMENT '版本',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT '权重',
  `enable` int(11) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `request_count` int(11) NOT NULL DEFAULT 0 COMMENT '接口请求次数',
  `category_id` int(11) NOT NULL DEFAULT 0 COMMENT '分类',
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default',
  `create_time` datetime NOT NULL COMMENT '安装时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `alias`(`alias`) USING BTREE,
  UNIQUE INDEX `class`(`class`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_plugin
-- ----------------------------
INSERT INTO `toolbox_plugin` VALUES (1, 'Hello，Pluto', 'If you see this message, it means that your program is running properly.', 'example', 'aoaostar_com\\example', '{}', 'v1.0', 0, 1, 0, 0, 'default', '2022-09-06 20:43:33', '2022-09-10 23:34:01');

-- ----------------------------
-- Table structure for toolbox_request
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_request`;
CREATE TABLE `toolbox_request`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_count` int(11) NOT NULL DEFAULT 0 COMMENT '接口请求次数',
  `plugin_id` int(11) NOT NULL COMMENT '分类',
  `create_time` datetime NOT NULL COMMENT '安装时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_request
-- ----------------------------

-- ----------------------------
-- Table structure for toolbox_user
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_user`;
CREATE TABLE `toolbox_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `stars` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标星json array',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `oauth` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'oauth信息json',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ip',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_user
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
