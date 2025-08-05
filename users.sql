-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 04 août 2025 à 16:36
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `maincourante`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `matricule` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sso_provider` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sso_id` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_method` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `telephone` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(355) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entite_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_entite_id_foreign` (`entite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `matricule`, `nom`, `prenom`, `email`, `sso_provider`, `sso_id`, `last_login_at`, `last_login_method`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `telephone`, `direction`, `fonction`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `entite_id`) VALUES
(1, '', 'Mouhamed', 'Faye', 'faymouhamed1994@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$sMWBYuo.4B6MzHfPFRJc2u2hI5twdHeKE01KU5dZ6sQPfWdQKlfzC', NULL, NULL, NULL, '778705491', 'DMSV', 'HOTLINE', NULL, NULL, NULL, '2025-05-11 05:59:19', '2025-07-10 20:00:03', 3),
(2, '', 'Sow', 'Cheikh Ahmad Sakhir', 'sasico7631@mytaemin.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$50wS0W1thTPGwDC0tiDed.RtpH8iuETQeHZje8DrL9O653nmcv212', NULL, NULL, NULL, '770696217', 'DSI', 'PLANIFICATEUR', NULL, NULL, NULL, '2025-05-11 06:11:54', '2025-07-14 21:47:11', 1),
(3, '', 'DIOP', 'Mouhamed', 'mouhaCEDXmazed.faye@seter.sn', NULL, NULL, NULL, NULL, NULL, '$2y$12$7YEH6ZhOHVs4Kj96XSW5luir8LkfrWEZ7qTE4lsRR/98f4tFsjEoS', NULL, NULL, NULL, '778705491', 'DMSV', 'CHEF CIRCULATION', NULL, NULL, NULL, '2025-05-11 06:29:13', '2025-05-11 06:29:13', NULL),
(4, '', 'Faye', 'Mouhamed', 'yinoso2364@inkight.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$jnFAyqfov3R4gIXuApBCveaKaUe3AJGUnq9Df1Hl0fgu/OU0zk7Ky', NULL, NULL, NULL, '777441256', 'DSI', 'CM', NULL, NULL, NULL, '2025-05-11 12:55:38', '2025-07-08 17:19:16', 1),
(9, '', 'Testeur 02', 'PAI', 'faymouhamed1@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$W.Tf3SlTSzsPnnJv4KfqKeKJVVVAet2syFAN20LBpTiB9BGMnV0Ky', NULL, NULL, NULL, '77662299', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-12 16:18:22', '2025-05-12 16:18:22', NULL),
(8, '', 'TesteurBETA', 'PAI', 'seterpai@seter.sn', NULL, NULL, NULL, NULL, NULL, '$2y$12$SB8BmZLe0u.2C8InohD.6ewUNWQ0013nQkhT8nOE9CJrqEq2nnlBO', NULL, NULL, NULL, '77662299', 'DSI', 'HOTLINE', NULL, NULL, NULL, '2025-05-12 13:22:54', '2025-05-12 13:26:20', NULL),
(10, '', 'Testeur 03', 'PAI', 'faymouhamed12@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$4KOMzcyZ0U00W.Db982is.Ai/G7Pfr83Ts9.HzJfTar90fJwfJMZW', NULL, NULL, NULL, '77662211', 'DSI', 'HOTLINE', NULL, NULL, NULL, '2025-05-12 16:19:01', '2025-05-12 16:19:01', NULL),
(11, '', 'Testeur 04', 'PAI', 'faymouhamed14@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$yKJUs8vmk0Y3e1xdKnCFO.35tnjz6D3ucSLT0YLm/6BGGRYxAOdQy', NULL, NULL, NULL, '77662222', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-12 16:19:41', '2025-05-12 16:19:41', NULL),
(13, '', 'NDIAYE', 'Lala', 'mouhamed.faye314@outlook.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$BgKR7rPi6jM7vVhvWs6QCev4pfR9FYzG2IEvR00b3CVEXi3PaADjG', NULL, NULL, NULL, '777441256', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-12 16:21:25', '2025-05-12 16:21:25', NULL),
(14, '', 'TESTPAI', 'Testeur', 'testpai@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$ZSehC.MEHYzXO4BBP2IalOnN2WStEDcBuLpki0L0sj9QbcC.xzU5i', NULL, NULL, NULL, '770696217', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-21 19:06:50', '2025-05-21 19:06:50', NULL),
(15, '', 'TESTPAI', 'Testeur', 'testpai2@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$hSt58uIzi02UkVwnHtNgfO7spNuNGU0UqlK3MK/efjjFQPN78qudO', NULL, NULL, NULL, '770696217', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-21 19:12:56', '2025-05-21 19:12:56', NULL),
(16, '', 'Sow', 'Cheikh Ahmad Sakhir', 'pai4@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$kZ0.VJ7emSBf59HjdnTK7eqV0oTP/vJmTQXGgsFO.BNSteYb7DIOS', NULL, NULL, NULL, '770696217', 'DSI', 'SUPERVISEUR COF', NULL, NULL, NULL, '2025-05-21 19:17:02', '2025-05-21 19:17:02', NULL),
(17, '', 'Mbaye', 'abou', 'abou@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$HjhVlV9UPkD2oeFaxHnqw.h8E5cmxc/zlhQrLJdUUhN0Ew7.9lc.m', NULL, NULL, NULL, '785000030', 'DEX', 'CM', NULL, NULL, NULL, '2025-05-21 19:27:20', '2025-07-12 21:27:19', 3),
(18, '', 'Mbaye', 'abou', 'zefresfv@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$TcBZkrtvYwjvAH/Prwc4N.LPJ/.yw3ULvLJes9cwzIg8O7wr9.ima', NULL, NULL, NULL, '785000030', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-22 19:34:24', '2025-05-22 19:34:24', NULL),
(19, '', 'OEDDS', 'Admin', 'rzdfdxed1994@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$f.TMdHVP0rFmI3Mqn8rDrOqO0ZN1cSg71ZJqXWwRMaID.exFTy7iK', NULL, NULL, NULL, '785000030', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-22 19:42:33', '2025-05-22 19:42:33', NULL),
(20, '', 'PAI', 'TESTEUR', 'pai123@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$5nglsWvuif3dzOCCsr62weJ55ok5HG/hR61g0fiA2/5yEyf5qOcMO', NULL, NULL, NULL, '770696217', 'DSI', 'CIV', NULL, NULL, NULL, '2025-05-22 23:11:19', '2025-07-11 19:04:54', 1),
(21, '', 'NANA', 'FAVA', 'nanana@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$qfMDPXPW.9aqMgvjhb2.s.EwyficdyF5GcXJx8k.aVCUvPQK2u09S', NULL, NULL, NULL, '770696217', 'DEX', NULL, NULL, NULL, NULL, '2025-05-26 15:18:58', '2025-05-26 15:36:38', 3),
(22, '206', 'DIAGNE', 'Aissatou', 'aissatou.diagne@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '781690727', 'DEX', NULL, NULL, NULL, NULL, '2025-07-20 10:56:43', '2025-07-20 10:56:43', NULL),
(23, '1120', 'CIVREIS', 'Charles Pardoux François', 'charles.civreis@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '773914984', NULL, NULL, NULL, NULL, NULL, '2025-07-20 11:05:24', '2025-07-20 11:26:15', 1),
(24, '20', 'SOW', 'Mohamed Saïdou', 'mohamed-saidou.sow@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '781739934', 'DEX', NULL, NULL, NULL, NULL, '2025-07-20 11:30:36', '2025-07-20 11:30:36', NULL),
(25, '21', 'THIAM', 'Adama', 'adama.thiam@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '781615088', 'DEX', 'Chef d\'équipe conduite', NULL, NULL, NULL, '2025-07-20 12:49:22', '2025-07-20 12:49:22', NULL),
(26, '23', 'TOUNKARA', 'Ndeye Fatou Marthe', 'fatou-marthe.tounkara@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '774334004', 'QRSE', 'RESPONSABLE QUALITE ET RSE', NULL, NULL, NULL, '2025-07-20 13:01:36', '2025-07-20 14:14:26', 5),
(27, '375', 'NDIAYE', 'Coura', 'coura.ndiaye@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '765567761', 'DMSV', 'Chef de Secteur', NULL, NULL, NULL, '2025-07-21 17:21:14', '2025-07-21 17:21:14', 1),
(28, '1409', 'FAYE', 'Mouhamed', 'mouhamed.faye@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '778705491', 'DSI', 'Chargé Suivi Outils Industriels', NULL, NULL, NULL, '2025-07-23 11:20:19', '2025-07-29 05:08:55', 5),
(29, '85', 'DIOP', 'Babacar', 'babacar.diop@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '766002619', 'DMSV', 'Chef de Secteur', NULL, NULL, NULL, '2025-07-23 15:26:45', '2025-08-04 08:51:53', 1),
(30, '930', 'DIA', 'Amadou Daouda', 'amadou-daouda.dia@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '777670756', 'DSI', 'Responsable Domaine, Architecture et Développement', NULL, NULL, NULL, '2025-07-23 15:28:53', '2025-07-29 05:34:25', 5),
(31, '12345', 'Fisrt', 'User', 'fistuser@seter?sn', NULL, NULL, NULL, NULL, NULL, '$2y$12$M6igcDcS542/b62etx13suDL1ZnEmizu/d/xOEJax/74kxypVZSzW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-29 11:22:39', '2025-07-29 11:22:39', NULL),
(32, '12345', 'First', 'User', 'first@seter.sn', NULL, NULL, NULL, NULL, NULL, '$2y$12$5ibbjiRa9ZmEO5Za3Y3AcO5OjvaaKnMVWPSsHKUWiyUqtrmuzXInK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-29 11:24:34', '2025-07-29 11:24:34', NULL),
(33, '12345', 'MonPremimer', 'USer', 'monpremieruser@seter.sn', NULL, NULL, NULL, NULL, NULL, '$2y$12$DLcthQX6URsb2CS0GZkD1u8MP4JztLbJLhyHoMkpSMfRCZzfNAjpK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-29 11:27:26', '2025-07-29 11:27:26', NULL),
(34, '1385', 'SALL', 'Thierno Alpha', 'thierno-alpha.sall@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '771014391', 'DSI', 'Assistant Chef de Projet', NULL, NULL, NULL, '2025-07-31 10:06:31', '2025-07-31 10:06:31', 1),
(35, '1225', 'THIAM', 'Rokhaya Samb', 'rokhaya.thiam@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '777670883', 'DSI', 'Administrateur Applicatif Industriel', NULL, NULL, NULL, '2025-08-04 08:51:15', '2025-08-04 08:51:15', 3),
(36, '197', 'DIOP', 'Mandaw', 'mandaw.diop@seter.sn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '783787082', 'DSI', 'Directeur des Systèmes d\'Informations', NULL, NULL, NULL, '2025-08-04 12:04:53', '2025-08-04 12:04:53', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
