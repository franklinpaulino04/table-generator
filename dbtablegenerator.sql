/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100416
 Source Host           : localhost:3306
 Source Schema         : dbtablegenerator

 Target Server Type    : MySQL
 Target Server Version : 100416
 File Encoding         : 65001

 Date: 31/05/2021 16:29:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ai_field_types
-- ----------------------------
DROP TABLE IF EXISTS `ai_field_types`;
CREATE TABLE `ai_field_types`  (
  `typeId` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`typeId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ai_field_types
-- ----------------------------

-- ----------------------------
-- Table structure for ai_tables
-- ----------------------------
DROP TABLE IF EXISTS `ai_tables`;
CREATE TABLE `ai_tables`  (
  `tableId` int NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `hidden` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`tableId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ai_tables
-- ----------------------------
INSERT INTO `ai_tables` VALUES (1, NULL, 1);
INSERT INTO `ai_tables` VALUES (2, NULL, 1);
INSERT INTO `ai_tables` VALUES (3, NULL, 1);

-- ----------------------------
-- Table structure for ai_tables_items
-- ----------------------------
DROP TABLE IF EXISTS `ai_tables_items`;
CREATE TABLE `ai_tables_items`  (
  `itemId` int NOT NULL AUTO_INCREMENT,
  `tableId` int NULL DEFAULT 0,
  `field_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `field_type` int NULL DEFAULT 0,
  `field_length` int NULL DEFAULT NULL,
  `field_decimal` decimal(20, 9) NULL DEFAULT NULL,
  `field_not_null` tinyint(1) NULL DEFAULT 0,
  `field_key` tinyint(1) NULL DEFAULT 0,
  `hidden` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ai_tables_items
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
