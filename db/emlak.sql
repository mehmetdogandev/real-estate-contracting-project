CREATE DATABASE IF NOT EXISTS `emlak` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `emlak`;

-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: emlak
-- ------------------------------------------------------
-- Server version	8.0.30
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!50503 SET NAMES utf8 */;

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;

/*!40103 SET TIME_ZONE='+00:00' */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_mesajlar`
--
DROP TABLE IF EXISTS `admin_mesajlar`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `admin_mesajlar` (
    `msj_id` int NOT NULL AUTO_INCREMENT,
    `msj_isim` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `msj_eposta` varchar(100) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `msj_konu` varchar(100) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `msj_mesaj` varchar(1000) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      PRIMARY KEY (`msj_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_mesajlar`
--
LOCK TABLES `admin_mesajlar` WRITE;

/*!40000 ALTER TABLE `admin_mesajlar` DISABLE KEYS */;

/*!40000 ALTER TABLE `admin_mesajlar` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `arsabilgi`
--
DROP TABLE IF EXISTS `arsabilgi`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `arsabilgi` (
    `id` int NOT NULL AUTO_INCREMENT,
    `arsa_urun_id` int NOT NULL,
    `imar_durumu` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `arsa_metrekare` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `metrekare_fiyat` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `ada_no` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `parsel_no` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `pafta_no` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `emsal` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `tapu_durumu` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `kat_karsiligi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `arsa_krediye_uygun` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `arsa_kimden` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `arsa_takas` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      PRIMARY KEY (`id`),
      KEY `fk_urun_id` (`arsa_urun_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 18 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arsabilgi`
--
LOCK TABLES `arsabilgi` WRITE;

/*!40000 ALTER TABLE `arsabilgi` DISABLE KEYS */;

INSERT INTO
  `arsabilgi`
VALUES
  (
    17,
    109,
    '',
    '245',
    '254151',
    '32',
    '3232',
    '43',
    '23',
    '',
    'Hayır',
    'Hayır',
    '39',
    'Evet'
  );

/*!40000 ALTER TABLE `arsabilgi` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `evarsa`
--
DROP TABLE IF EXISTS `evarsa`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `evarsa` (
    `id` int NOT NULL AUTO_INCREMENT,
    `ilanTuru` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evarsa`
--
LOCK TABLES `evarsa` WRITE;

/*!40000 ALTER TABLE `evarsa` DISABLE KEYS */;

INSERT INTO
  `evarsa`
VALUES
  (1, 'Ev'),
  (2, 'Arsa'),
  (3, 'Proje');

/*!40000 ALTER TABLE `evarsa` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `evbilgi`
--
DROP TABLE IF EXISTS `evbilgi`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `evbilgi` (
    `id` int NOT NULL AUTO_INCREMENT,
    `ev_urun_id` int NOT NULL,
    `ev_tipi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `ev_metrekare` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `oda_sayisi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `bina_yasi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `kat_sayisi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `isitma` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `banyo_sayisi` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `esyali` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `kullanim_durumu` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `site_icinde` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `aidat` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `ev_krediye_uygun` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `ev_kimden` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `ev_takas` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      PRIMARY KEY (`id`),
      KEY `fk_urun_id` (`ev_urun_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 42 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evbilgi`
--
LOCK TABLES `evbilgi` WRITE;

/*!40000 ALTER TABLE `evbilgi` DISABLE KEYS */;

INSERT INTO
  `evbilgi`
VALUES
  (
    36,
    107,
    'DubleX',
    '24',
    '5',
    '2',
    '2',
    'Doğal Gaz',
    '1',
    'Hayır',
    'Evet',
    'Evet',
    '25',
    'Hayır',
    '40',
    'Hayır'
  ),
  (
    37,
    108,
    'Apartman Dairesi',
    '24',
    '11',
    '5',
    '22',
    'Kömürlü Kalorifer',
    '8',
    'Hayır',
    'Hayır',
    'Evet',
    '115',
    'Evet',
    '39',
    'Evet'
  ),
  (
    38,
    110,
    'Villa',
    '546',
    '251515',
    '5151',
    '15',
    'Soba',
    '51',
    'Evet',
    'Evet',
    'Evet',
    '34',
    'Evet',
    '39',
    'Evet'
  ),
  (
    39,
    126,
    'DubleX',
    '565',
    '56',
    '56',
    '56',
    'Soba',
    '56',
    'Evet',
    'Evet',
    'Evet',
    '565',
    'Evet',
    '39',
    'Evet'
  ),
  (
    40,
    111,
    'DubleX',
    '343',
    '433',
    '34',
    '34',
    'Doğal Gaz',
    '43',
    'Evet',
    'Evet',
    'Evet',
    '34',
    'Evet',
    '39',
    'Evet'
  ),
  (
    41,
    112,
    'Villa',
    '23',
    '23',
    '23',
    '34',
    'Soba',
    '34',
    'Evet',
    'Evet',
    'Evet',
    '34',
    'Evet',
    '39',
    'Evet'
  );

/*!40000 ALTER TABLE `evbilgi` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `gonderen_mail`
--
DROP TABLE IF EXISTS `gonderen_mail`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `gonderen_mail` (
    `id` int NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `smtp_host` varchar(255) NOT NULL DEFAULT 'smtp.gmail.com',
    `smtp_port` int NOT NULL DEFAULT '587',
    `smtp_secure` varchar(10) NOT NULL DEFAULT 'tls',
    `aktif` tinyint (1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gonderen_mail`
--
LOCK TABLES `gonderen_mail` WRITE;

/*!40000 ALTER TABLE `gonderen_mail` DISABLE KEYS */;

INSERT INTO
  `gonderen_mail`
VALUES
  (
    1,
    'mehmetdogan.dev@gmail.com',
    'pbet xztq vcnv aili',
    'smtp.gmail.com',
    587,
    'tls',
    1
  );

/*!40000 ALTER TABLE `gonderen_mail` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `gonderilenler`
--
DROP TABLE IF EXISTS `gonderilenler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `gonderilenler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `kisi_id` int DEFAULT NULL,
    `gonderme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `kisi_id` (`kisi_id`),
    CONSTRAINT `gonderilenler_ibfk_1` FOREIGN KEY (`kisi_id`) REFERENCES `kisiler` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 494 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gonderilenler`
--
LOCK TABLES `gonderilenler` WRITE;

/*!40000 ALTER TABLE `gonderilenler` DISABLE KEYS */;

INSERT INTO
  `gonderilenler`
VALUES
  (469, 28, '2025-05-10 07:17:46'),
  (470, 28, '2025-05-10 07:19:10'),
  (471, 28, '2025-05-10 07:50:00'),
  (472, 28, '2025-05-10 10:19:26'),
  (473, 28, '2025-05-10 10:34:43'),
  (474, 28, '2025-05-10 10:47:12'),
  (475, 28, '2025-05-10 11:23:49'),
  (476, 28, '2025-05-10 11:27:20'),
  (477, 28, '2025-05-10 11:30:11'),
  (478, 28, '2025-05-10 11:32:33'),
  (479, 28, '2025-05-10 11:35:43'),
  (480, 28, '2025-05-10 11:36:33'),
  (481, 28, '2025-05-10 18:02:19'),
  (482, 28, '2025-05-10 18:23:21'),
  (483, 28, '2025-05-10 18:33:14'),
  (484, 28, '2025-05-10 18:56:02'),
  (485, 28, '2025-05-10 18:57:56'),
  (486, 29, '2025-05-11 07:28:06'),
  (487, 28, '2025-05-11 07:28:11'),
  (489, 29, '2025-05-17 08:56:32'),
  (490, 28, '2025-05-17 08:56:36'),
  (492, 29, '2025-06-01 19:20:33'),
  (493, 28, '2025-06-01 19:20:40');

/*!40000 ALTER TABLE `gonderilenler` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `il`
--
DROP TABLE IF EXISTS `il`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `il` (
    `id` int NOT NULL,
    `sehir` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `il`
--
LOCK TABLES `il` WRITE;

/*!40000 ALTER TABLE `il` DISABLE KEYS */;

INSERT INTO
  `il`
VALUES
  (1, 'Adana'),
  (2, 'Adıyaman'),
  (3, 'Afyonkarahisar'),
  (4, 'Ağrı'),
  (5, 'Amasya'),
  (6, 'Ankara'),
  (7, 'Antalya'),
  (8, 'Artvin'),
  (9, 'Aydın'),
  (10, 'Balıkesir'),
  (11, 'Bilecik'),
  (12, 'Bingöl'),
  (13, 'Bitlis'),
  (14, 'Bolu'),
  (15, 'Burdur'),
  (16, 'Bursa'),
  (17, 'Çanakkale'),
  (18, 'Çankırı'),
  (19, 'Çorum'),
  (20, 'Denizli'),
  (21, 'Diyarbakır'),
  (22, 'Edirne'),
  (23, 'Elâzığ'),
  (24, 'Erzincan'),
  (25, 'Erzurum'),
  (26, 'Eskişehir'),
  (27, 'Gaziantep'),
  (28, 'Giresun'),
  (29, 'Gümüşhane'),
  (30, 'Hakkâri'),
  (31, 'Hatay'),
  (32, 'Isparta'),
  (33, 'Mersin'),
  (34, 'İstanbul'),
  (35, 'İzmir'),
  (36, 'Kars'),
  (37, 'Kastamonu'),
  (38, 'Kayseri'),
  (39, 'Kırklareli'),
  (40, 'Kırşehir'),
  (41, 'Kocaeli'),
  (42, 'Konya'),
  (43, 'Kütahya'),
  (44, 'Malatya'),
  (45, 'Manisa'),
  (46, 'Kahramanmaraş'),
  (47, 'Mardin'),
  (48, 'Muğla'),
  (49, 'Muş'),
  (50, 'Nevşehir'),
  (51, 'Niğde'),
  (52, 'Ordu'),
  (53, 'Rize'),
  (54, 'Sakarya'),
  (55, 'Samsun'),
  (56, 'Siirt'),
  (57, 'Sinop'),
  (58, 'Sivas'),
  (59, 'Tekirdağ'),
  (60, 'Tokat'),
  (61, 'Trabzon'),
  (62, 'Tunceli'),
  (63, 'Şanlıurfa'),
  (64, 'Uşak'),
  (65, 'Van'),
  (66, 'Yozgat'),
  (67, 'Zonguldak'),
  (68, 'Aksaray'),
  (69, 'Bayburt'),
  (70, 'Karaman'),
  (71, 'Kırıkkale'),
  (72, 'Batman'),
  (73, 'Şırnak'),
  (74, 'Bartın'),
  (75, 'Ardahan'),
  (76, 'Iğdır'),
  (77, 'Yalova'),
  (78, 'Karabük'),
  (79, 'Kilis'),
  (80, 'Osmaniye'),
  (81, 'Düzce');

/*!40000 ALTER TABLE `il` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `ilce`
--
DROP TABLE IF EXISTS `ilce`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `ilce` (
    `id` int NOT NULL,
    `ilce` varchar(55) DEFAULT NULL,
    `il_id` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_il_id` (`il_id`) USING BTREE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ilce`
--
LOCK TABLES `ilce` WRITE;

/*!40000 ALTER TABLE `ilce` DISABLE KEYS */;

INSERT INTO
  `ilce`
VALUES
  (1, 'Abana', 37),
  (2, 'Acıgöl', 50),
  (3, 'Acıpayam', 20),
  (4, 'Adaklı', 12),
  (5, 'Adalar', 34),
  (6, 'Adapazarı', 54),
  (7, 'Adıyaman', 2),
  (8, 'Adilcevaz', 13),
  (9, 'Afşin', 46),
  (10, 'Afyonkarahisar', 3),
  (11, 'Ağaçören', 68),
  (12, 'Ağın', 23),
  (13, 'Ağlasun', 15),
  (14, 'Ağlı', 37),
  (15, 'Ağrı', 4),
  (16, 'Ahırlı', 42),
  (17, 'Ahlat', 13),
  (18, 'Ahmetli', 45),
  (19, 'Akçaabat', 61),
  (20, 'Akçadağ', 44),
  (21, 'Akçakale', 63),
  (22, 'Akçakent', 40),
  (23, 'Akçakoca', 81),
  (24, 'Akdağmadeni', 66),
  (25, 'Akdeniz', 33),
  (26, 'Akhisar', 45),
  (27, 'Akıncılar', 58),
  (28, 'Akkışla', 38),
  (29, 'Akkuş', 52),
  (30, 'Akören', 42),
  (31, 'Akpınar', 40),
  (32, 'Aksaray', 68),
  (33, 'Akseki', 7),
  (34, 'Aksu', 7),
  (35, 'Aksu', 32),
  (36, 'Akşehir', 42),
  (37, 'Akyaka', 36),
  (38, 'Akyazı', 54),
  (39, 'Akyurt', 6),
  (40, 'Alaca', 19),
  (41, 'Alacakaya', 23),
  (42, 'Alaçam', 55),
  (43, 'Aladağ', 1),
  (44, 'Alanya', 7),
  (45, 'Alaplı', 67),
  (46, 'Alaşehir', 45),
  (47, 'Aliağa', 35),
  (48, 'Almus', 60),
  (49, 'Alpu', 26),
  (50, 'Altıeylül', 10),
  (51, 'Altındağ', 6),
  (52, 'Altınekin', 42),
  (53, 'Altınordu', 52),
  (54, 'Altınova', 77),
  (55, 'Altınözü', 31),
  (56, 'Altıntaş', 43),
  (57, 'Altınyayla', 15),
  (58, 'Altınyayla', 58),
  (59, 'Altunhisar', 51),
  (60, 'Alucra', 28),
  (61, 'Amasra', 74),
  (62, 'Amasya', 5),
  (63, 'Anamur', 33),
  (64, 'Andırın', 46),
  (65, 'Antakya', 31),
  (66, 'Araban', 27),
  (67, 'Araç', 37),
  (68, 'Araklı', 61),
  (69, 'Aralık', 76),
  (70, 'Arapgir', 44),
  (71, 'Ardahan', 75),
  (72, 'Ardanuç', 8),
  (73, 'Ardeşen', 53),
  (74, 'Arguvan', 44),
  (75, 'Arhavi', 8),
  (76, 'Arıcak', 23),
  (77, 'Arifiye', 54),
  (78, 'Armutlu', 77),
  (79, 'Arnavutköy', 34),
  (80, 'Arpaçay', 36),
  (81, 'Arsin', 61),
  (82, 'Arsuz', 31),
  (83, 'Artova', 60),
  (84, 'Artuklu', 47),
  (85, 'Artvin', 8),
  (86, 'Asarcık', 55),
  (87, 'Aslanapa', 43),
  (88, 'Aşkale', 25),
  (89, 'Atabey', 32),
  (90, 'Atakum', 55),
  (91, 'Ataşehir', 34),
  (92, 'Atkaracalar', 18),
  (93, 'Avanos', 50),
  (94, 'Avcılar', 34),
  (95, 'Ayancık', 57),
  (96, 'Ayaş', 6),
  (97, 'Aybastı', 52),
  (98, 'Aydıncık', 33),
  (99, 'Aydıncık', 66),
  (100, 'Aydıntepe', 69),
  (101, 'Ayrancı', 70),
  (102, 'Ayvacık', 17),
  (103, 'Ayvacık', 55),
  (104, 'Ayvalık', 10),
  (105, 'Azdavay', 37),
  (106, 'Aziziye', 25),
  (107, 'Babadağ', 20),
  (108, 'Babaeski', 39),
  (109, 'Bafra', 55),
  (110, 'Bağcılar', 34),
  (111, 'Bağlar', 21),
  (112, 'Bahçe', 80),
  (113, 'Bahçelievler', 34),
  (114, 'Bahçesaray', 65),
  (115, 'Bahşili', 71),
  (116, 'Bakırköy', 34),
  (117, 'Baklan', 20),
  (118, 'Bala', 6),
  (119, 'Balçova', 35),
  (120, 'Balışeyh', 71),
  (121, 'Balya', 10),
  (122, 'Banaz', 64),
  (123, 'Bandırma', 10),
  (124, 'Bartın', 74),
  (125, 'Baskil', 23),
  (126, 'Başakşehir', 34),
  (127, 'Başçiftlik', 60),
  (128, 'Başiskele', 41),
  (129, 'Başkale', 65),
  (130, 'Başmakçı', 3),
  (131, 'Başyayla', 70),
  (132, 'Batman', 72),
  (133, 'Battalgazi', 44),
  (134, 'Bayat', 3),
  (135, 'Bayat', 19),
  (136, 'Bayburt', 69),
  (137, 'Bayındır', 35),
  (138, 'Baykan', 56),
  (139, 'Bayraklı', 35),
  (140, 'Bayramiç', 17),
  (141, 'Bayramören', 18),
  (142, 'Bayrampaşa', 34),
  (143, 'Bekilli', 20),
  (144, 'Belen', 31),
  (145, 'Bergama', 35),
  (146, 'Besni', 2),
  (147, 'Beşikdüzü', 61),
  (148, 'Beşiktaş', 34),
  (149, 'Beşiri', 72),
  (150, 'Beyağaç', 20),
  (151, 'Beydağ', 35),
  (152, 'Beykoz', 34),
  (153, 'Beylikdüzü', 34),
  (154, 'Beylikova', 26),
  (155, 'Beyoğlu', 34),
  (156, 'Beypazarı', 6),
  (157, 'Beyşehir', 42),
  (158, 'Beytüşşebap', 73),
  (159, 'Biga', 17),
  (160, 'Bigadiç', 10),
  (161, 'Bilecik', 11),
  (162, 'Bingöl', 12),
  (163, 'Birecik', 63),
  (164, 'Bismil', 21),
  (165, 'Bitlis', 13),
  (166, 'Bodrum', 48),
  (167, 'Boğazkale', 19),
  (168, 'Boğazlıyan', 66),
  (169, 'Bolu', 14),
  (170, 'Bolvadin', 3),
  (171, 'Bor', 51),
  (172, 'Borçka', 8),
  (173, 'Bornova', 35),
  (174, 'Boyabat', 57),
  (175, 'Bozcaada', 17),
  (176, 'Bozdoğan', 9),
  (177, 'Bozkır', 42),
  (178, 'Bozkurt', 20),
  (179, 'Bozkurt', 37),
  (180, 'Bozova', 63),
  (181, 'Boztepe', 40),
  (182, 'Bozüyük', 11),
  (183, 'Bozyazı', 33),
  (184, 'Buca', 35),
  (185, 'Bucak', 15),
  (186, 'Buharkent', 9),
  (187, 'Bulancak', 28),
  (188, 'Bulanık', 49),
  (189, 'Buldan', 20),
  (190, 'Burdur', 15),
  (191, 'Burhaniye', 10),
  (192, 'Bünyan', 38),
  (193, 'Büyükçekmece', 34),
  (194, 'Büyükorhan', 16),
  (195, 'Canik', 55),
  (196, 'Ceyhan', 1),
  (197, 'Ceylanpınar', 63),
  (198, 'Cide', 37),
  (199, 'Cihanbeyli', 42),
  (200, 'Cizre', 73),
  (201, 'Cumayeri', 81),
  (202, 'Çağlayancerit', 46),
  (203, 'Çal', 20),
  (204, 'Çaldıran', 65),
  (205, 'Çamardı', 51),
  (206, 'Çamaş', 52),
  (207, 'Çameli', 20),
  (208, 'Çamlıdere', 6),
  (209, 'Çamlıhemşin', 53),
  (210, 'Çamlıyayla', 33),
  (211, 'Çamoluk', 28),
  (212, 'Çan', 17),
  (213, 'Çanakçı', 28),
  (214, 'Çanakkale', 17),
  (215, 'Çandır', 66),
  (216, 'Çankaya', 6),
  (217, 'Çankırı', 18),
  (218, 'Çardak', 20),
  (219, 'Çarşamba', 55),
  (220, 'Çarşıbaşı', 61),
  (221, 'Çat', 25),
  (222, 'Çatak', 65),
  (223, 'Çatalca', 34),
  (224, 'Çatalpınar', 52),
  (225, 'Çatalzeytin', 37),
  (226, 'Çavdarhisar', 43),
  (227, 'Çavdır', 15),
  (228, 'Çay', 3),
  (229, 'Çaybaşı', 52),
  (230, 'Çaycuma', 67),
  (231, 'Çayeli', 53),
  (232, 'Çayıralan', 66),
  (233, 'Çayırlı', 24),
  (234, 'Çayırova', 41),
  (235, 'Çaykara', 61),
  (236, 'Çekerek', 66),
  (237, 'Çekmeköy', 34),
  (238, 'Çelebi', 71),
  (239, 'Çelikhan', 2),
  (240, 'Çeltik', 42),
  (241, 'Çeltikçi', 15),
  (242, 'Çemişgezek', 62),
  (243, 'Çerkeş', 18),
  (244, 'Çerkezköy', 59),
  (245, 'Çermik', 21),
  (246, 'Çeşme', 35),
  (247, 'Çıldır', 75),
  (248, 'Çınar', 21),
  (249, 'Çınarcık', 77),
  (250, 'Çiçekdağı', 40),
  (251, 'Çifteler', 26),
  (252, 'Çiftlik', 51),
  (253, 'Çiftlikköy', 77),
  (254, 'Çiğli', 35),
  (255, 'Çilimli', 81),
  (256, 'Çine', 9),
  (257, 'Çivril', 20),
  (258, 'Çobanlar', 3),
  (259, 'Çorlu', 59),
  (260, 'Çorum', 19),
  (261, 'Çubuk', 6),
  (262, 'Çukurca', 30),
  (263, 'Çukurova', 1),
  (264, 'Çumra', 42),
  (265, 'Çüngüş', 21),
  (266, 'Daday', 37),
  (267, 'Dalaman', 48),
  (268, 'Damal', 75),
  (269, 'Darende', 44),
  (270, 'Dargeçit', 47),
  (271, 'Darıca', 41),
  (272, 'Datça', 48),
  (273, 'Dazkırı', 3),
  (274, 'Defne', 31),
  (275, 'Delice', 71),
  (276, 'Demirci', 45),
  (277, 'Demirköy', 39),
  (278, 'Demirözü', 69),
  (279, 'Demre', 7),
  (280, 'Derbent', 42),
  (281, 'Derebucak', 42),
  (282, 'Dereli', 28),
  (283, 'Derepazarı', 53),
  (284, 'Derik', 47),
  (285, 'Derince', 41),
  (286, 'Derinkuyu', 50),
  (287, 'Dernekpazarı', 61),
  (288, 'Develi', 38),
  (289, 'Devrek', 67),
  (290, 'Devrekani', 37),
  (291, 'Dicle', 21),
  (292, 'Didim', 9),
  (293, 'Digor', 36),
  (294, 'Dikili', 35),
  (295, 'Dikmen', 57),
  (296, 'Dilovası', 41),
  (297, 'Dinar', 3),
  (298, 'Divriği', 58),
  (299, 'Diyadin', 4),
  (300, 'Dodurga', 19),
  (301, 'Doğanhisar', 42),
  (302, 'Doğankent', 28),
  (303, 'Doğanşar', 58),
  (304, 'Doğanşehir', 44),
  (305, 'Doğanyol', 44),
  (306, 'Doğanyurt', 37),
  (307, 'Doğubayazıt', 4),
  (308, 'Domaniç', 43),
  (309, 'Dörtdivan', 14),
  (310, 'Dörtyol', 31),
  (311, 'Döşemealtı', 7),
  (312, 'Dulkadiroğlu', 46),
  (313, 'Dumlupınar', 43),
  (314, 'Durağan', 57),
  (315, 'Dursunbey', 10),
  (316, 'Düzce', 81),
  (317, 'Düziçi', 80),
  (318, 'Düzköy', 61),
  (319, 'Eceabat', 17),
  (320, 'Edirne', 22),
  (321, 'Edremit', 10),
  (322, 'Edremit', 65),
  (323, 'Efeler', 9),
  (324, 'Eflani', 78),
  (325, 'Eğil', 21),
  (326, 'Eğirdir', 32),
  (327, 'Ekinözü', 46),
  (328, 'Elazığ', 23),
  (329, 'Elbeyli', 79),
  (330, 'Elbistan', 46),
  (331, 'Eldivan', 18),
  (332, 'Eleşkirt', 4),
  (333, 'Elmadağ', 6),
  (334, 'Elmalı', 7),
  (335, 'Emet', 43),
  (336, 'Emirdağ', 3),
  (337, 'Emirgazi', 42),
  (338, 'Enez', 22),
  (339, 'Erbaa', 60),
  (340, 'Erciş', 65),
  (341, 'Erdek', 10),
  (342, 'Erdemli', 33),
  (343, 'Ereğli', 42),
  (344, 'Ereğli', 67),
  (345, 'Erenler', 54),
  (346, 'Erfelek', 57),
  (347, 'Ergani', 21),
  (348, 'Ergene', 59),
  (349, 'Ermenek', 70),
  (350, 'Eruh', 56),
  (351, 'Erzin', 31),
  (352, 'Erzincan', 24),
  (353, 'Esenler', 34),
  (354, 'Esenyurt', 34),
  (355, 'Eskil', 68),
  (356, 'Eskipazar', 78),
  (357, 'Espiye', 28),
  (358, 'Eşme', 64),
  (359, 'Etimesgut', 6),
  (360, 'Evciler', 3),
  (361, 'Evren', 6),
  (362, 'Eynesil', 28),
  (363, 'Eyüp', 34),
  (364, 'Eyyübiye', 63),
  (365, 'Ezine', 17),
  (366, 'Fatih', 34),
  (367, 'Fatsa', 52),
  (368, 'Feke', 1),
  (369, 'Felahiye', 38),
  (370, 'Ferizli', 54),
  (371, 'Fethiye', 48),
  (372, 'Fındıklı', 53),
  (373, 'Finike', 7),
  (374, 'Foça', 35),
  (375, 'Gaziemir', 35),
  (376, 'Gaziosmanpaşa', 34),
  (377, 'Gazipaşa', 7),
  (378, 'Gebze', 41),
  (379, 'Gediz', 43),
  (380, 'Gelendost', 32),
  (381, 'Gelibolu', 17),
  (382, 'Gemerek', 58),
  (383, 'Gemlik', 16),
  (384, 'Genç', 12),
  (385, 'Gercüş', 72),
  (386, 'Gerede', 14),
  (387, 'Gerger', 2),
  (388, 'Germencik', 9),
  (389, 'Gerze', 57),
  (390, 'Gevaş', 65),
  (391, 'Geyve', 54),
  (392, 'Giresun', 28),
  (393, 'Gökçeada', 17),
  (394, 'Gökçebey', 67),
  (395, 'Göksun', 46),
  (396, 'Gölbaşı', 2),
  (397, 'Gölbaşı', 6),
  (398, 'Gölcük', 41),
  (399, 'Göle', 75),
  (400, 'Gölhisar', 15),
  (401, 'Gölköy', 52),
  (402, 'Gölmarmara', 45),
  (403, 'Gölova', 58),
  (404, 'Gölpazarı', 11),
  (405, 'Gölyaka', 81),
  (406, 'Gömeç', 10),
  (407, 'Gönen', 10),
  (408, 'Gönen', 32),
  (409, 'Gördes', 45),
  (410, 'Görele', 28),
  (411, 'Göynücek', 5),
  (412, 'Göynük', 14),
  (413, 'Güce', 28),
  (414, 'Güçlükonak', 73),
  (415, 'Güdül', 6),
  (416, 'Gülağaç', 68),
  (417, 'Gülnar', 33),
  (418, 'Gülşehir', 50),
  (419, 'Gülyalı', 52),
  (420, 'Gümüşhacıköy', 5),
  (421, 'Gümüşhane', 29),
  (422, 'Gümüşova', 81),
  (423, 'Gündoğmuş', 7),
  (424, 'Güney', 20),
  (425, 'Güneysınır', 42),
  (426, 'Güneysu', 53),
  (427, 'Güngören', 34),
  (428, 'Günyüzü', 26),
  (429, 'Gürgentepe', 52),
  (430, 'Güroymak', 13),
  (431, 'Gürpınar', 65),
  (432, 'Gürsu', 16),
  (433, 'Gürün', 58),
  (434, 'Güzelbahçe', 35),
  (435, 'Güzelyurt', 68),
  (436, 'Hacıbektaş', 50),
  (437, 'Hacılar', 38),
  (438, 'Hadim', 42),
  (439, 'Hafik', 58),
  (440, 'Hakkâri', 30),
  (441, 'Halfeti', 63),
  (442, 'Haliliye', 63),
  (443, 'Halkapınar', 42),
  (444, 'Hamamözü', 5),
  (445, 'Hamur', 4),
  (446, 'Han', 26),
  (447, 'Hanak', 75),
  (448, 'Hani', 21),
  (449, 'Hanönü', 37),
  (450, 'Harmancık', 16),
  (451, 'Harran', 63),
  (452, 'Hasanbeyli', 80),
  (453, 'Hasankeyf', 72),
  (454, 'Hasköy', 49),
  (455, 'Hassa', 31),
  (456, 'Havran', 10),
  (457, 'Havsa', 22),
  (458, 'Havza', 55),
  (459, 'Haymana', 6),
  (460, 'Hayrabolu', 59),
  (461, 'Hayrat', 61),
  (462, 'Hazro', 21),
  (463, 'Hekimhan', 44),
  (464, 'Hemşin', 53),
  (465, 'Hendek', 54),
  (466, 'Hınıs', 25),
  (467, 'Hilvan', 63),
  (468, 'Hisarcık', 43),
  (469, 'Hizan', 13),
  (470, 'Hocalar', 3),
  (471, 'Honaz', 20),
  (472, 'Hopa', 8),
  (473, 'Horasan', 25),
  (474, 'Hozat', 62),
  (475, 'Hüyük', 42),
  (476, 'Iğdır', 76),
  (477, 'Ilgaz', 18),
  (478, 'Ilgın', 42),
  (479, 'Isparta', 32),
  (480, 'İbradı', 7),
  (481, 'İdil', 73),
  (482, 'İhsangazi', 37),
  (483, 'İhsaniye', 3),
  (484, 'İkizce', 52),
  (485, 'İkizdere', 53),
  (486, 'İliç', 24),
  (487, 'İlkadım', 55),
  (488, 'İmamoğlu', 1),
  (489, 'İmranlı', 58),
  (490, 'İncesu', 38),
  (491, 'İncirliova', 9),
  (492, 'İnebolu', 37),
  (493, 'İnegöl', 16),
  (494, 'İnhisar', 11),
  (495, 'İnönü', 26),
  (496, 'İpekyolu', 65),
  (497, 'İpsala', 22),
  (498, 'İscehisar', 3),
  (499, 'İskenderun', 31),
  (500, 'İskilip', 19),
  (501, 'İslahiye', 27),
  (502, 'İspir', 25),
  (503, 'İvrindi', 10),
  (504, 'İyidere', 53),
  (505, 'İzmit', 41),
  (506, 'İznik', 16),
  (507, 'Kabadüz', 52),
  (508, 'Kabataş', 52),
  (509, 'Kadıköy', 34),
  (510, 'Kadınhanı', 42),
  (511, 'Kadışehri', 66),
  (512, 'Kadirli', 80),
  (513, 'Kağıthane', 34),
  (514, 'Kağızman', 36),
  (515, 'Kahta', 2),
  (516, 'Kale', 20),
  (517, 'Kale', 44),
  (518, 'Kalecik', 6),
  (519, 'Kalkandere', 53),
  (520, 'Kaman', 40),
  (521, 'Kandıra', 41),
  (522, 'Kangal', 58),
  (523, 'Kapaklı', 59),
  (524, 'Karabağlar', 35),
  (525, 'Karaburun', 35),
  (526, 'Karabük', 78),
  (527, 'Karacabey', 16),
  (528, 'Karacasu', 9),
  (529, 'Karaçoban', 25),
  (530, 'Karahallı', 64),
  (531, 'Karaisalı', 1),
  (532, 'Karakeçili', 71),
  (533, 'Karakoçan', 23),
  (534, 'Karakoyunlu', 76),
  (535, 'Karaköprü', 63),
  (536, 'Karaman', 70),
  (537, 'Karamanlı', 15),
  (538, 'Karamürsel', 41),
  (539, 'Karapınar', 42),
  (540, 'Karapürçek', 54),
  (541, 'Karasu', 54),
  (542, 'Karataş', 1),
  (543, 'Karatay', 42),
  (544, 'Karayazı', 25),
  (545, 'Karesi', 10),
  (546, 'Kargı', 19),
  (547, 'Karkamış', 27),
  (548, 'Karlıova', 12),
  (549, 'Karpuzlu', 9),
  (550, 'Kars', 36),
  (551, 'Karşıyaka', 35),
  (552, 'Kartal', 34),
  (553, 'Kartepe', 41),
  (554, 'Kastamonu', 37),
  (555, 'Kaş', 7),
  (556, 'Kavak', 55),
  (557, 'Kavaklıdere', 48),
  (558, 'Kayapınar', 21),
  (559, 'Kaynarca', 54),
  (560, 'Kaynaşlı', 81),
  (561, 'Kazan', 6),
  (562, 'Kazımkarabekir', 70),
  (563, 'Keban', 23),
  (564, 'Keçiborlu', 32),
  (565, 'Keçiören', 6),
  (566, 'Keles', 16),
  (567, 'Kelkit', 29),
  (568, 'Kemah', 24),
  (569, 'Kemaliye', 24),
  (570, 'Kemalpaşa', 35),
  (571, 'Kemer', 7),
  (572, 'Kemer', 15),
  (573, 'Kepez', 7),
  (574, 'Kepsut', 10),
  (575, 'Keskin', 71),
  (576, 'Kestel', 16),
  (577, 'Keşan', 22),
  (578, 'Keşap', 28),
  (579, 'Kıbrıscık', 14),
  (580, 'Kınık', 35),
  (581, 'Kırıkhan', 31),
  (582, 'Kırıkkale', 71),
  (583, 'Kırkağaç', 45),
  (584, 'Kırklareli', 39),
  (585, 'Kırşehir', 40),
  (586, 'Kızılcahamam', 6),
  (587, 'Kızılırmak', 18),
  (588, 'Kızılören', 3),
  (589, 'Kızıltepe', 47),
  (590, 'Kiğı', 12),
  (591, 'Kilimli', 67),
  (592, 'Kilis', 79),
  (593, 'Kiraz', 35),
  (594, 'Kocaali', 54),
  (595, 'Kocaköy', 21),
  (596, 'Kocasinan', 38),
  (597, 'Koçarlı', 9),
  (598, 'Kofçaz', 39),
  (599, 'Konak', 35),
  (600, 'Konyaaltı', 7),
  (601, 'Korgan', 52),
  (602, 'Korgun', 18),
  (603, 'Korkut', 49),
  (604, 'Korkuteli', 7),
  (605, 'Kovancılar', 23),
  (606, 'Koyulhisar', 58),
  (607, 'Kozaklı', 50),
  (608, 'Kozan', 1),
  (609, 'Kozlu', 67),
  (610, 'Kozluk', 72),
  (611, 'Köprübaşı', 45),
  (612, 'Köprübaşı', 61),
  (613, 'Köprüköy', 25),
  (614, 'Körfez', 41),
  (615, 'Köse', 29),
  (616, 'Köşk', 9),
  (617, 'Köyceğiz', 48),
  (618, 'Kula', 45),
  (619, 'Kulp', 21),
  (620, 'Kulu', 42),
  (621, 'Kuluncak', 44),
  (622, 'Kumlu', 31),
  (623, 'Kumluca', 7),
  (624, 'Kumru', 52),
  (625, 'Kurşunlu', 18),
  (626, 'Kurtalan', 56),
  (627, 'Kurucaşile', 74),
  (628, 'Kuşadası', 9),
  (629, 'Kuyucak', 9),
  (630, 'Küçükçekmece', 34),
  (631, 'Küre', 37),
  (632, 'Kürtün', 29),
  (633, 'Kütahya', 43),
  (634, 'Laçin', 19),
  (635, 'Ladik', 55),
  (636, 'Lalapaşa', 22),
  (637, 'Lapseki', 17),
  (638, 'Lice', 21),
  (639, 'Lüleburgaz', 39),
  (640, 'Maçka', 61),
  (641, 'Maden', 23),
  (642, 'Mahmudiye', 26),
  (643, 'Malazgirt', 49),
  (644, 'Malkara', 59),
  (645, 'Maltepe', 34),
  (646, 'Mamak', 6),
  (647, 'Manavgat', 7),
  (648, 'Manyas', 10),
  (649, 'Marmara', 10),
  (650, 'Marmaraereğlisi', 59),
  (651, 'Marmaris', 48),
  (652, 'Mazgirt', 62),
  (653, 'Mazıdağı', 47),
  (654, 'Mecitözü', 19),
  (655, 'Melikgazi', 38),
  (656, 'Menderes', 35),
  (657, 'Menemen', 35),
  (658, 'Mengen', 14),
  (659, 'Menteşe', 48),
  (660, 'Meram', 42),
  (661, 'Meriç', 22),
  (662, 'Merkezefendi', 20),
  (663, 'Merzifon', 5),
  (664, 'Mesudiye', 52),
  (665, 'Mezitli', 33),
  (666, 'Midyat', 47),
  (667, 'Mihalgazi', 26),
  (668, 'Mihalıççık', 26),
  (669, 'Milas', 48),
  (670, 'Mucur', 40),
  (671, 'Mudanya', 16),
  (672, 'Mudurnu', 14),
  (673, 'Muradiye', 65),
  (674, 'Muratlı', 59),
  (675, 'Muratpaşa', 7),
  (676, 'Murgul', 8),
  (677, 'Musabeyli', 79),
  (678, 'Mustafakemalpaşa', 16),
  (679, 'Muş', 49),
  (680, 'Mut', 33),
  (681, 'Mutki', 13),
  (682, 'Nallıhan', 6),
  (683, 'Narlıdere', 35),
  (684, 'Narman', 25),
  (685, 'Nazımiye', 62),
  (686, 'Nazilli', 9),
  (687, 'Nevşehir', 50),
  (688, 'Niğde', 51),
  (689, 'Niksar', 60),
  (690, 'Nilüfer', 16),
  (691, 'Nizip', 27),
  (692, 'Nurdağı', 27),
  (693, 'Nurhak', 46),
  (694, 'Nusaybin', 47),
  (695, 'Odunpazarı', 26),
  (696, 'Of', 61),
  (697, 'Oğuzeli', 27),
  (698, 'Oğuzlar', 19),
  (699, 'Oltu', 25),
  (700, 'Olur', 25),
  (701, 'Ondokuzmayıs', 55),
  (702, 'Onikişubat', 46),
  (703, 'Orhaneli', 16),
  (704, 'Orhangazi', 16),
  (705, 'Orta', 18),
  (706, 'Ortaca', 48),
  (707, 'Ortahisar', 61),
  (708, 'Ortaköy', 68),
  (709, 'Ortaköy', 19),
  (710, 'Osmancık', 19),
  (711, 'Osmaneli', 11),
  (712, 'Osmangazi', 16),
  (713, 'Osmaniye', 80),
  (714, 'Otlukbeli', 24),
  (715, 'Ovacık', 78),
  (716, 'Ovacık', 62),
  (717, 'Ödemiş', 35),
  (718, 'Ömerli', 47),
  (719, 'Özalp', 65),
  (720, 'Özvatan', 38),
  (721, 'Palandöken', 25),
  (722, 'Palu', 23),
  (723, 'Pamukkale', 20),
  (724, 'Pamukova', 54),
  (725, 'Pasinler', 25),
  (726, 'Patnos', 4),
  (727, 'Payas', 31),
  (728, 'Pazar', 53),
  (729, 'Pazar', 60),
  (730, 'Pazarcık', 46),
  (731, 'Pazarlar', 43),
  (732, 'Pazaryeri', 11),
  (733, 'Pazaryolu', 25),
  (734, 'Pehlivanköy', 39),
  (735, 'Pendik', 34),
  (736, 'Perşembe', 52),
  (737, 'Pertek', 62),
  (738, 'Pervari', 56),
  (739, 'Pınarbaşı', 37),
  (740, 'Pınarbaşı', 38),
  (741, 'Pınarhisar', 39),
  (742, 'Piraziz', 28),
  (743, 'Polateli', 79),
  (744, 'Polatlı', 6),
  (745, 'Posof', 75),
  (746, 'Pozantı', 1),
  (747, 'Pursaklar', 6),
  (748, 'Pülümür', 62),
  (749, 'Pütürge', 44),
  (750, 'Refahiye', 24),
  (751, 'Reşadiye', 60),
  (752, 'Reyhanlı', 31),
  (753, 'Rize', 53),
  (754, 'Safranbolu', 78),
  (755, 'Saimbeyli', 1),
  (756, 'Salıpazarı', 55),
  (757, 'Salihli', 45),
  (758, 'Samandağ', 31),
  (759, 'Samsat', 2),
  (760, 'Sancaktepe', 34),
  (761, 'Sandıklı', 3),
  (762, 'Sapanca', 54),
  (763, 'Saray', 59),
  (764, 'Saray', 65),
  (765, 'Saraydüzü', 57),
  (766, 'Saraykent', 66),
  (767, 'Sarayköy', 20),
  (768, 'Sarayönü', 42),
  (769, 'Sarıcakaya', 26),
  (770, 'Sarıçam', 1),
  (771, 'Sarıgöl', 45),
  (772, 'Sarıkamış', 36),
  (773, 'Sarıkaya', 66),
  (774, 'Sarıoğlan', 38),
  (775, 'Sarıveliler', 70),
  (776, 'Sarıyahşi', 68),
  (777, 'Sarıyer', 34),
  (778, 'Sarız', 38),
  (779, 'Saruhanlı', 45),
  (780, 'Sason', 72),
  (781, 'Savaştepe', 10),
  (782, 'Savur', 47),
  (783, 'Seben', 14),
  (784, 'Seferihisar', 35),
  (785, 'Selçuk', 35),
  (786, 'Selçuklu', 42),
  (787, 'Selendi', 45),
  (788, 'Selim', 36),
  (789, 'Senirkent', 32),
  (790, 'Serdivan', 54),
  (791, 'Serik', 7),
  (792, 'Serinhisar', 20),
  (793, 'Seydikemer', 48),
  (794, 'Seydiler', 37),
  (795, 'Seydişehir', 42),
  (796, 'Seyhan', 1),
  (797, 'Seyitgazi', 26),
  (798, 'Sındırgı', 10),
  (799, 'Siirt', 56),
  (800, 'Silifke', 33),
  (801, 'Silivri', 34),
  (802, 'Silopi', 73),
  (803, 'Silvan', 21),
  (804, 'Simav', 43),
  (805, 'Sinanpaşa', 3),
  (806, 'Sincan', 6),
  (807, 'Sincik', 2),
  (808, 'Sinop', 57),
  (809, 'Sivas', 58),
  (810, 'Sivaslı', 64),
  (811, 'Siverek', 63),
  (812, 'Sivrice', 23),
  (813, 'Sivrihisar', 26),
  (814, 'Solhan', 12),
  (815, 'Soma', 45),
  (816, 'Sorgun', 66),
  (817, 'Söğüt', 11),
  (818, 'Söğütlü', 54),
  (819, 'Söke', 9),
  (820, 'Sulakyurt', 71),
  (821, 'Sultanbeyli', 34),
  (822, 'Sultandağı', 3),
  (823, 'Sultangazi', 34),
  (824, 'Sultanhisar', 9),
  (825, 'Suluova', 5),
  (826, 'Sulusaray', 60),
  (827, 'Sumbas', 80),
  (828, 'Sungurlu', 19),
  (829, 'Sur', 21),
  (830, 'Suruç', 63),
  (831, 'Susurluk', 10),
  (832, 'Susuz', 36),
  (833, 'Suşehri', 58),
  (834, 'Süleymanpaşa', 59),
  (835, 'Süloğlu', 22),
  (836, 'Sürmene', 61),
  (837, 'Sütçüler', 32),
  (838, 'Şabanözü', 18),
  (839, 'Şahinbey', 27),
  (840, 'Şalpazarı', 61),
  (841, 'Şaphane', 43),
  (842, 'Şarkışla', 58),
  (843, 'Şarkikaraağaç', 32),
  (844, 'Şarköy', 59),
  (845, 'Şavşat', 8),
  (846, 'Şebinkarahisar', 28),
  (847, 'Şefaatli', 66),
  (848, 'Şehitkamil', 27),
  (849, 'Şehzadeler', 45),
  (850, 'Şemdinli', 30),
  (851, 'Şenkaya', 25),
  (852, 'Şenpazar', 37),
  (853, 'Şereflikoçhisar', 6),
  (854, 'Şırnak', 73),
  (855, 'Şile', 34),
  (856, 'Şiran', 29),
  (857, 'Şirvan', 56),
  (858, 'Şişli', 34),
  (859, 'Şuhut', 3),
  (860, 'Talas', 38),
  (861, 'Taraklı', 54),
  (862, 'Tarsus', 33),
  (863, 'Taşkent', 42),
  (864, 'Taşköprü', 37),
  (865, 'Taşlıçay', 4),
  (866, 'Taşova', 5),
  (867, 'Tatvan', 13),
  (868, 'Tavas', 20),
  (869, 'Tavşanlı', 43),
  (870, 'Tefenni', 15),
  (871, 'Tekkeköy', 55),
  (872, 'Tekman', 25),
  (873, 'Tepebaşı', 26),
  (874, 'Tercan', 24),
  (875, 'Termal', 77),
  (876, 'Terme', 55),
  (877, 'Tillo', 56),
  (878, 'Tire', 35),
  (879, 'Tirebolu', 28),
  (880, 'Tokat', 60),
  (881, 'Tomarza', 38),
  (882, 'Tonya', 61),
  (883, 'Toprakkale', 80),
  (884, 'Torbalı', 35),
  (885, 'Toroslar', 33),
  (886, 'Tortum', 25),
  (887, 'Torul', 29),
  (888, 'Tosya', 37),
  (889, 'Tufanbeyli', 1),
  (890, 'Tunceli', 62),
  (891, 'Turgutlu', 45),
  (892, 'Turhal', 60),
  (893, 'Tuşba', 65),
  (894, 'Tut', 2),
  (895, 'Tutak', 4),
  (896, 'Tuzla', 34),
  (897, 'Tuzluca', 76),
  (898, 'Tuzlukçu', 42),
  (899, 'Türkeli', 57),
  (900, 'Türkoğlu', 46),
  (901, 'Uğurludağ', 19),
  (902, 'Ula', 48),
  (903, 'Ulaş', 58),
  (904, 'Ulubey', 52),
  (905, 'Ulubey', 64),
  (906, 'Uluborlu', 32),
  (907, 'Uludere', 73),
  (908, 'Ulukışla', 51),
  (909, 'Ulus', 74),
  (910, 'Urla', 35),
  (911, 'Uşak', 64),
  (912, 'Uzundere', 25),
  (913, 'Uzunköprü', 22),
  (914, 'Ümraniye', 34),
  (915, 'Ünye', 52),
  (916, 'Ürgüp', 50),
  (917, 'Üsküdar', 34),
  (918, 'Üzümlü', 24),
  (919, 'Vakfıkebir', 61),
  (920, 'Varto', 49),
  (921, 'Vezirköprü', 55),
  (922, 'Viranşehir', 63),
  (923, 'Vize', 39),
  (924, 'Yağlıdere', 28),
  (925, 'Yahşihan', 71),
  (926, 'Yahyalı', 38),
  (927, 'Yakakent', 55),
  (928, 'Yakutiye', 25),
  (929, 'Yalıhüyük', 42),
  (930, 'Yalova', 77),
  (931, 'Yalvaç', 32),
  (932, 'Yapraklı', 18),
  (933, 'Yatağan', 48),
  (934, 'Yavuzeli', 27),
  (935, 'Yayladağı', 31),
  (936, 'Yayladere', 12),
  (937, 'Yazıhan', 44),
  (938, 'Yedisu', 12),
  (939, 'Yenice', 17),
  (940, 'Yenice', 78),
  (941, 'Yeniçağa', 14),
  (942, 'Yenifakılı', 66),
  (943, 'Yenimahalle', 6),
  (944, 'Yenipazar', 9),
  (945, 'Yenipazar', 11),
  (946, 'Yenişarbademli', 32),
  (947, 'Yenişehir', 16),
  (948, 'Yenişehir', 21),
  (949, 'Yenişehir', 33),
  (950, 'Yerköy', 66),
  (951, 'Yeşilhisar', 38),
  (952, 'Yeşilli', 47),
  (953, 'Yeşilova', 15),
  (954, 'Yeşilyurt', 44),
  (955, 'Yeşilyurt', 60),
  (956, 'Yığılca', 81),
  (957, 'Yıldırım', 16),
  (958, 'Yıldızeli', 58),
  (959, 'Yomra', 61),
  (960, 'Yozgat', 66),
  (961, 'Yumurtalık', 1),
  (962, 'Yunak', 42),
  (963, 'Yunusemre', 45),
  (964, 'Yusufeli', 8),
  (965, 'Yüksekova', 30),
  (966, 'Yüreğir', 1),
  (967, 'Zara', 58),
  (968, 'Zeytinburnu', 34),
  (969, 'Zile', 60),
  (970, 'Zonguldak', 67),
  (971, 'Kemalpaşa', 8),
  (972, 'Sultanhanı', 68);

/*!40000 ALTER TABLE `ilce` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `kategoriler`
--
DROP TABLE IF EXISTS `kategoriler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `kategoriler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `kategoriadi` varchar(128) NOT NULL,
    `aciklama` text NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 14 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategoriler`
--
LOCK TABLES `kategoriler` WRITE;

/*!40000 ALTER TABLE `kategoriler` DISABLE KEYS */;

INSERT INTO
  `kategoriler`
VALUES
  (11, 'Kiralık', 'Kiralık evler ve arsalar.'),
  (12, 'Satılık', 'Satılık evler ve arslar.'),
  (
    13,
    'Günlük Kiralık',
    'Günü birlik kiralanabilen evler ve arsalar.'
  );

/*!40000 ALTER TABLE `kategoriler` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `kisiler`
--
DROP TABLE IF EXISTS `kisiler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `kisiler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `ad` varchar(100) NOT NULL,
    `soyad` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `son_gonderilen_email_tarih` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 31 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kisiler`
--
LOCK TABLES `kisiler` WRITE;

/*!40000 ALTER TABLE `kisiler` DISABLE KEYS */;

INSERT INTO
  `kisiler`
VALUES
  (
    28,
    'Mehmet',
    'DOĞAN',
    'mehmetdogan.dev@gmail.com',
    '2025-05-10 07:16:11'
  ),
  (
    29,
    'Yusuf',
    ' Bozkurt',
    'ysfbzkrt2015@gmail.com',
    '2025-05-11 07:27:49'
  );

/*!40000 ALTER TABLE `kisiler` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `kullanicilar`
--
DROP TABLE IF EXISTS `kullanicilar`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `kullanicilar` (
    `id` int NOT NULL AUTO_INCREMENT,
    `adsoyad` varchar(30) NOT NULL,
    `kadi` varchar(20) NOT NULL,
    `sifre` varchar(20) NOT NULL,
    `eposta` varchar(70) NOT NULL,
    `tel_no` varchar(13) NOT NULL,
    `profil_resmi` varchar(255) DEFAULT NULL,
    `onay` varchar(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    UNIQUE KEY `eposta` (`eposta`),
    UNIQUE KEY `kadi` (`kadi`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 45 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kullanicilar`
--
LOCK TABLES `kullanicilar` WRITE;

/*!40000 ALTER TABLE `kullanicilar` DISABLE KEYS */;

INSERT INTO
  `kullanicilar`
VALUES
  (
    39,
    'test',
    'test',
    'test',
    'test@gmail.com',
    '5123456789',
    '/content/images/profil/683ca89e0cf51.jpeg',
    '1'
  ),
  (
    44,
    'Mehmet DOĞAN',
    'mehmetdogandev',
    'mehmetdogandev',
    'mehmetdogan.dev@gmail.com',
    '5535643218',
    NULL,
    '2'
  );

/*!40000 ALTER TABLE `kullanicilar` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `kullanicilar_mesaj`
--
DROP TABLE IF EXISTS `kullanicilar_mesaj`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `kullanicilar_mesaj` (
    `k_msj_id` int NOT NULL AUTO_INCREMENT,
    `k_msj_kimden` int NOT NULL,
    `k_msj_kime` int NOT NULL,
    `k_msj_konu` varchar(500) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `k_msj_icerik` varchar(1000) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `k_msj_tarih` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`k_msj_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 71 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kullanicilar_mesaj`
--
LOCK TABLES `kullanicilar_mesaj` WRITE;

/*!40000 ALTER TABLE `kullanicilar_mesaj` DISABLE KEYS */;

INSERT INTO
  `kullanicilar_mesaj`
VALUES
  (
    55,
    44,
    39,
    'Yeni arsa ilanı',
    'srfgdhjmgjck',
    '2025-06-01 19:34:48'
  ),
  (
    56,
    39,
    44,
    'Yeni arsa ilanı',
    'resgfdhkgj',
    '2025-06-01 19:37:08'
  ),
  (
    57,
    39,
    44,
    'Yeni arsa ilanı',
    'resgfdhkgj',
    '2025-06-01 19:37:23'
  ),
  (
    58,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:37:47'
  ),
  (
    59,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:37:56'
  ),
  (
    60,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:02'
  ),
  (
    61,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:38:04'
  ),
  (
    62,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:11'
  ),
  (
    63,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:38:12'
  ),
  (
    64,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:19'
  ),
  (
    65,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:38:20'
  ),
  (
    66,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:27'
  ),
  (
    67,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:35'
  ),
  (
    68,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:44'
  ),
  (
    69,
    39,
    44,
    'Yeni arsa ilanı',
    'srhfdjg',
    '2025-06-01 19:38:44'
  ),
  (
    70,
    44,
    39,
    'Yeni arsa ilanı',
    'fgsfhdgjkfgjöçkh.',
    '2025-06-01 19:38:52'
  );

/*!40000 ALTER TABLE `kullanicilar_mesaj` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `logo`
--
DROP TABLE IF EXISTS `logo`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `logo` (
    `logo_id` int NOT NULL AUTO_INCREMENT,
    `logo_aciklama` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `logo_baglanti` varchar(500) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `logo_k_durum` int NOT NULL,
      PRIMARY KEY (`logo_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logo`
--
LOCK TABLES `logo` WRITE;

/*!40000 ALTER TABLE `logo` DISABLE KEYS */;

INSERT INTO
  `logo`
VALUES
  (
    4,
    'Yas ilan edildiğinde kullandığımız Logo',
    '60880a66c5563-yas-logo.png',
    0
  ),
  (
    7,
    'Asıl kullandığımız Logo',
    '606f154e20524-logo.png',
    0
  ),
  (8, 'Kutlama logosu', '60880a12190be-logo.png', 0),
  (9, 'proje logo', '6639ca8cdcbd0-logo.png', 0),
  (10, 'WALLS', '664d0d30dc411-logo-stiky.png', 1),
  (11, 'WALLS2', '664d0d3bae07b-logo-light.png', 0);

/*!40000 ALTER TABLE `logo` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `projeler`
--
DROP TABLE IF EXISTS `projeler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `projeler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `urunadi` varchar(128) NOT NULL,
    `il_id` int NOT NULL,
    `ilce_id` int NOT NULL,
    `evarsa_id` int NOT NULL,
    `aciklama` text NOT NULL,
    `fiyat` double NOT NULL,
    `giris_tarihi` datetime NOT NULL,
    `dzltm_tarihi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `resim` varchar(128) DEFAULT NULL,
    `resim_iki` varchar(128) NOT NULL,
    `resim_uc` varchar(128) NOT NULL,
    `resim_dort` varchar(128) NOT NULL,
    `kategori_id` int DEFAULT NULL,
    `onay` varchar(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `fk_kategori_id` (`kategori_id`),
    KEY `fk_evarsa_id` (`evarsa_id`),
    KEY `fk_il_id` (`il_id`),
    KEY `fk_ilce_id` (`ilce_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 127 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projeler`
--
LOCK TABLES `projeler` WRITE;

/*!40000 ALTER TABLE `projeler` DISABLE KEYS */;

INSERT INTO
  `projeler`
VALUES
  (
    126,
    'dfgdhg',
    15,
    400,
    3,
    'fdhgjfh',
    4545,
    '2025-05-10 10:25:52',
    '2025-05-10 10:25:57',
    '681f29b0eee07-detay-html.png',
    '681f29b0eee0d-index-dental-save.png',
    '',
    '',
    7,
    '1'
  );

/*!40000 ALTER TABLE `projeler` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `projeler_kategoriler`
--
DROP TABLE IF EXISTS `projeler_kategoriler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `projeler_kategoriler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `kategoriadi` varchar(128) NOT NULL,
    `aciklama` text NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projeler_kategoriler`
--
LOCK TABLES `projeler_kategoriler` WRITE;

/*!40000 ALTER TABLE `projeler_kategoriler` DISABLE KEYS */;

INSERT INTO
  `projeler_kategoriler`
VALUES
  (7, 'Kategori-1', '');

/*!40000 ALTER TABLE `projeler_kategoriler` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `slider`
--
DROP TABLE IF EXISTS `slider`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `slider` (
    `slider_id` int NOT NULL AUTO_INCREMENT,
    `slider_baslik` varchar(50) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `slider_aciklama` varchar(100) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `slider_baglanti` varchar(100) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      `slider_k_durum` varchar(1) CHARACTER
    SET
      utf8mb3 COLLATE utf8mb3_turkish_ci NOT NULL,
      PRIMARY KEY (`slider_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8mb3 COLLATE = utf8mb3_turkish_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--
LOCK TABLES `slider` WRITE;

/*!40000 ALTER TABLE `slider` DISABLE KEYS */;

INSERT INTO
  `slider`
VALUES
  (
    7,
    'Slider-1 Deneme',
    'Slider-1 deneme, açıklama deneme.',
    '6079de9de847a-slider1.png',
    '1'
  ),
  (
    8,
    'Slider-2 Deneme',
    'Slider-2 deneme, açıklama deneme.',
    '6079df019514e-slider2.png',
    '2'
  ),
  (
    9,
    'Slider-3 Deneme',
    'Slider-3 deneme, açıklama deneme.',
    '6079df0f41401-slider3.png',
    '3'
  );

/*!40000 ALTER TABLE `slider` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `urunler`
--
DROP TABLE IF EXISTS `urunler`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `urunler` (
    `id` int NOT NULL AUTO_INCREMENT,
    `urunadi` varchar(128) NOT NULL,
    `il_id` int NOT NULL,
    `ilce_id` int NOT NULL,
    `evarsa_id` int NOT NULL,
    `aciklama` text NOT NULL,
    `fiyat` double NOT NULL,
    `giris_tarihi` datetime NOT NULL,
    `dzltm_tarihi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `resim` varchar(128) DEFAULT NULL,
    `resim_iki` varchar(128) NOT NULL,
    `resim_uc` varchar(128) NOT NULL,
    `resim_dort` varchar(128) NOT NULL,
    `kategori_id` int DEFAULT NULL,
    `onay` varchar(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `fk_kategori_id` (`kategori_id`),
    KEY `fk_evarsa_id` (`evarsa_id`),
    KEY `fk_il_id` (`il_id`),
    KEY `fk_ilce_id` (`ilce_id`),
    CONSTRAINT `fk_kategori_id` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 113 DEFAULT CHARSET = utf8mb3;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urunler`
--
LOCK TABLES `urunler` WRITE;

/*!40000 ALTER TABLE `urunler` DISABLE KEYS */;

INSERT INTO
  `urunler`
VALUES
  (
    107,
    'sadfgshasadfadsgadsgf',
    16,
    712,
    1,
    'dsgsafd',
    54545,
    '2025-05-10 07:14:11',
    '2025-05-10 09:15:04',
    '681efcc3d96b5-demo-video-cover.png',
    '681efcc3d96ba-detay-html.png',
    '',
    '',
    12,
    '1'
  ),
  (
    108,
    'New İlan',
    16,
    576,
    1,
    'saşdkfajg',
    1545,
    '2025-05-10 07:18:49',
    '2025-05-10 09:55:30',
    '681efdd9375ae-index-gecmis-islem-kayitlari.png',
    '681efdd9375b1-responcive.png',
    '',
    '681efdd9375b3-banner.png',
    13,
    '1'
  ),
  (
    109,
    'Yeni arsa ilanı',
    16,
    712,
    2,
    'dsfghdgjf',
    52,
    '2025-05-10 09:55:22',
    '2025-05-10 09:55:29',
    '681f228ab5c6f-dental-select.png',
    '681f228ab5c77-index-dental-save.png',
    '',
    '',
    12,
    '1'
  ),
  (
    110,
    'Yeni ev',
    11,
    494,
    1,
    'dfşgkh',
    2845,
    '2025-05-10 09:57:01',
    '2025-05-10 09:57:06',
    '681f22ed84d34-detay-html.png',
    '681f22ed84d3e-index-dental-save.png',
    '681f22ed84d40-responcive.png',
    '',
    11,
    '1'
  ),
  (
    111,
    'safdgfhj',
    17,
    159,
    1,
    'aresdtfghfj',
    2345,
    '2025-05-10 12:35:00',
    '2025-05-10 13:47:38',
    '681f47f44b077-banner.png',
    '681f47f44b07d-responcive.png',
    '681f47f44b080-detay-kalan.png',
    '681f47f44b082-demo-video-cover.png',
    12,
    '1'
  ),
  (
    112,
    'safdgsfhdgjf',
    15,
    190,
    1,
    'adsfhdgjfkghjl',
    2324234344,
    '2025-05-10 13:45:16',
    '2025-05-10 13:47:38',
    '681f586ce5ae6-60805449c7f98-rvz3__Small_.jpg',
    '681f586ce5af1-664cfc4c0ffdf-indir (7).jpeg',
    '681f586ce5af2-681f22ed84d34-detay-html.png',
    '681f586ce5af4-664cfc4c0ffe5-indir (3).jpeg',
    12,
    '1'
  );

/*!40000 ALTER TABLE `urunler` ENABLE KEYS */;

UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-19 21:25:57