/*
 Navicat Premium Data Transfer

 Source Server         : lnpp-8-mysql
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : 17.17.17.5:3306
 Source Schema         : db_absensi

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 25/06/2023 19:27:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for jadwal_presensi
-- ----------------------------
DROP TABLE IF EXISTS `jadwal_presensi`;
CREATE TABLE `jadwal_presensi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `tgl_absen` date NOT NULL,
  `waktu_dispensasi` int(255) NOT NULL,
  `mata_kuliah_id` bigint(20) NOT NULL,
  `timestamp_akhir_presensi` datetime DEFAULT NULL,
  KEY `id` (`id`),
  KEY `jam_masuk` (`jam_masuk`),
  KEY `jadwal_presensi_id_user_foreign` (`user_id`),
  KEY `jadwal_presensi_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `jadwal_presensi_id_user_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jadwal_presensi_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of jadwal_presensi
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for mahasiswa_enroll
-- ----------------------------
DROP TABLE IF EXISTS `mahasiswa_enroll`;
CREATE TABLE `mahasiswa_enroll` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `mata_kuliah_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `mahasiswa_enroll_mata_kuliah_foreign_id` (`mata_kuliah_id`),
  KEY `mahasiswa_enroll_user_id_foreign` (`user_id`),
  CONSTRAINT `mahasiswa_enroll_mata_kuliah_foreign_id` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_enroll_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of mahasiswa_enroll
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for mata_kuliah
-- ----------------------------
DROP TABLE IF EXISTS `mata_kuliah`;
CREATE TABLE `mata_kuliah` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `enroll_code` varchar(255) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `waktu_absen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mata_kuliah_user_id_foreign` (`user_id`),
  CONSTRAINT `mata_kuliah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mata_kuliah
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for presensi_mahasiswa
-- ----------------------------
DROP TABLE IF EXISTS `presensi_mahasiswa`;
CREATE TABLE `presensi_mahasiswa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_jadwal_presensi` bigint(20) NOT NULL,
  `id_mahasiswa` bigint(20) NOT NULL,
  `jam_presensi` time NOT NULL,
  `tgl_presensi` date NOT NULL,
  `waktu_telat` int(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `geo_coordinate` varchar(255) NOT NULL,
  `waktu_izin` int(255) NOT NULL DEFAULT '0',
  `waktu_sakit` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_mahasiswa_foreign` (`id_mahasiswa`),
  KEY `id_jadwal_presensi_foreign` (`id_jadwal_presensi`),
  CONSTRAINT `id_jadwal_presensi_foreign` FOREIGN KEY (`id_jadwal_presensi`) REFERENCES `jadwal_presensi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of presensi_mahasiswa
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of setting
-- ----------------------------
BEGIN;
INSERT INTO `setting` VALUES (1, 'auth', 'logo-poltek.jpg');
INSERT INTO `setting` VALUES (2, 'sidebar', 'logo-poltek.jpg');
INSERT INTO `setting` VALUES (3, 'surat', 'logo-poltek.jpg');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nomor_induk` varchar(255) DEFAULT NULL COMMENT 'mahasiswa dan dosen',
  `nama` varchar(255) NOT NULL COMMENT 'mahasiswa dan dosen',
  `tgl_lahir` date DEFAULT NULL COMMENT 'mahasiswa dan dosen',
  `alamat` varchar(255) DEFAULT NULL COMMENT 'mahasiswa dan dosen',
  `role` smallint(3) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL COMMENT 'mahasiswa',
  `tempat_lahir` varchar(255) DEFAULT NULL COMMENT 'mahasiswa',
  `angkatan` int(255) DEFAULT NULL COMMENT 'mahasiswa',
  `moto_hidup` varchar(255) DEFAULT NULL COMMENT 'mahasiswa',
  `kemampuan_pribadi` varchar(255) DEFAULT NULL COMMENT 'mahasiswa',
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `prodi` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, NULL, 'admin', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$QHwST/QZ1JzlyqLY5dER6e/ZQCsMO24jmsjWpITodeHPekiEYA5ka', 'admin', NULL, NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
