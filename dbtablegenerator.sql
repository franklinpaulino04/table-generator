/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100411
 Source Host           : localhost:3306
 Source Schema         : dbtablegenerator

 Target Server Type    : MySQL
 Target Server Version : 100411
 File Encoding         : 65001

 Date: 31/05/2021 22:57:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ai_field_types
-- ----------------------------
DROP TABLE IF EXISTS `ai_field_types`;
CREATE TABLE `ai_field_types`  (
  `typeId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`typeId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ai_field_types
-- ----------------------------
INSERT INTO `ai_field_types` VALUES (1, 'varchar');
INSERT INTO `ai_field_types` VALUES (2, 'int');
INSERT INTO `ai_field_types` VALUES (3, 'decimal');
INSERT INTO `ai_field_types` VALUES (4, 'text');

-- ----------------------------
-- Table structure for ai_tables
-- ----------------------------
DROP TABLE IF EXISTS `ai_tables`;
CREATE TABLE `ai_tables`  (
  `tableId` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `hidden` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`tableId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_tables_items
-- ----------------------------
DROP TABLE IF EXISTS `ai_tables_items`;
CREATE TABLE `ai_tables_items`  (
  `itemId` int(11) NOT NULL AUTO_INCREMENT,
  `tableId` int(11) NULL DEFAULT 0,
  `field_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `field_typeId` int(11) NULL DEFAULT 0,
  `field_length` int(11) NULL DEFAULT NULL,
  `field_key` tinyint(1) NULL DEFAULT 0,
  `field_default` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `hidden` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
