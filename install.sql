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
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_config
-- ----------------------------
INSERT INTO `toolbox_config` VALUES (1, 'global.title', '傲星工具箱', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (2, 'global.subtitle', '一个非常Nice的在线工具箱', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (3, 'global.keywords', '傲星工具箱,在线工具箱,aoaostar,pluto', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (4, 'global.description', '这是一个非常Nice的在线工具箱', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (5, 'global.template', 'default', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (6, 'global.foot_code', '\r\n            <div style=\"display: none\">\r\n                <script type=\"text/javascript\" src=\"https://s9.cnzz.com/z_stat.php?id=1280727911&web_id=1280727911\"></script>\r\n            </div>', '2021-12-22 21:34:41', '2021-12-22 21:35:57');
INSERT INTO `toolbox_config` VALUES (7, 'oauth.admin_path', 'admin', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (8, 'oauth.username', 'aoaostar', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (9, 'oauth.client_id', '', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (10, 'oauth.client_secret', '', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (11, 'cloud.mirror', 'https://github.com/{owner}/{repo}/raw/{branch}/{path}', '2021-12-22 18:31:59', '2021-12-22 18:31:59');
INSERT INTO `toolbox_config` VALUES (12, 'cdn.npm', 'https://cdn.jsdelivr.net/npm', '2022-03-02 17:56:15', '2022-03-02 18:13:43');
INSERT INTO `toolbox_config` VALUES (13, 'cdn.cdnjs', 'https://cdn.staticfile.org', '2022-03-02 17:56:15', '2022-03-02 17:56:15');

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_migration
-- ----------------------------

-- ----------------------------
-- Table structure for toolbox_plugin
-- ----------------------------
DROP TABLE IF EXISTS `toolbox_plugin`;
CREATE TABLE `toolbox_plugin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '插件标题',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '插件logo',
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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_plugin
-- ----------------------------
INSERT INTO `toolbox_plugin` VALUES (1, 'Hello，Pluto', '/static/icons/aoaostar_com_example.png', 'If you see this message, it means that your program is running properly', 'example', 'aoaostar_com\\example', '{}', 'v1.0', 0, 1, 0, 1, 'default', '2021-12-22 18:38:35', '2021-12-25 13:50:14');

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
  `stars` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标星',
  `avatar_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '头像',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of toolbox_user
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
