-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- GÃ©nÃ©rÃ© le :  Jeu 21 Mai 2015 Ã  18:31
-- Version du serveur :  5.6.21
-- Version de PHP :  5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de donnÃ©es :  `airejeux`
--

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE IF NOT EXISTS `departement` (
`id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `soundex` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hc_key` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `departement`
--

INSERT INTO `departement` (`id`, `code`, `nom`, `slug`, `soundex`, `hc_key`) VALUES
(1, '01', 'Ain', 'ain', 'A500', 'fr-v-ai'),
(2, '02', 'Aisne', 'aisne', 'A250', 'fr-s-as'),
(3, '03', 'Allier', 'allier', 'A460', 'fr-c-al'),
(4, '04', 'Alpes-de-Haute-Provence', 'alpes-de-haute-provence', 'A412316152', 'fr-u-ap'),
(5, '05', 'Hautes-Alpes', 'hautes-alpes', 'H32412', 'fr-u-ha'),
(6, '06', 'Alpes-Maritimes', 'alpes-maritimes', 'A41256352', 'fr-u-am'),
(7, '07', 'ArdÃ¨che', 'ardeche', 'A632', 'fr-v-ah'),
(8, '08', 'Ardennes', 'ardennes', 'A6352', 'fr-g-an'),
(9, '09', 'AriÃ¨ge', 'ariege', 'A620', 'fr-n-ag'),
(10, '10', 'Aube', 'aube', 'A100', 'fr-g-ab'),
(11, '11', 'Aude', 'aude', 'A300', 'fr-k-ad'),
(12, '12', 'Aveyron', 'aveyron', 'A165', 'fr-n-av'),
(13, '13', 'Bouches-du-RhÃ´ne', 'bouches-du-rhone', 'B2365', 'fr-u-bd'),
(14, '14', 'Calvados', 'calvados', 'C4132', 'fr-p-cv'),
(15, '15', 'Cantal', 'cantal', 'C534', 'fr-c-cl'),
(16, '16', 'Charente', 'charente', 'C653', 'fr-t-ct'),
(17, '17', 'Charente-Maritime', 'charente-maritime', 'C6535635', 'fr-t-cm'),
(18, '18', 'Cher', 'cher', 'C600', 'fr-f-ch'),
(19, '19', 'CorrÃ¨ze', 'correze', 'C620', 'fr-l-cz'),
(21, '21', 'CÃ´te-d''or', 'cote-dor', 'C360', 'fr-d-co'),
(22, '22', 'CÃ´tes-d''armor', 'cotes-darmor', 'C323656', 'fr-e-ca'),
(23, '23', 'Creuse', 'creuse', 'C620', 'fr-l-cr'),
(24, '24', 'Dordogne', 'dordogne', 'D6325', 'fr-b-dd'),
(25, '25', 'Doubs', 'doubs', 'D120', 'fr-i-db'),
(26, '26', 'DrÃ´me', 'drome', 'D650', 'fr-v-dm'),
(27, '27', 'Eure', 'eure', 'E600', 'fr-q-eu'),
(28, '28', 'Eure-et-Loir', 'eure-et-loir', 'E6346', 'fr-f-el'),
(29, '29', 'FinistÃ¨re', 'finistere', 'F5236', 'fr-e-fi'),
(30, '30', 'Gard', 'gard', 'G630', 'fr-k-ga'),
(31, '31', 'Haute-Garonne', 'haute-garonne', 'H3265', 'fr-n-hg'),
(32, '32', 'Gers', 'gers', 'G620', 'fr-n-ge'),
(33, '33', 'Gironde', 'gironde', 'G653', 'fr-b-gi'),
(34, '34', 'HÃ©rault', 'herault', 'H643', 'fr-k-he'),
(35, '35', 'Ile-et-Vilaine', 'ile-et-vilaine', 'I43145', 'fr-e-iv'),
(36, '36', 'Indre', 'indre', 'I536', 'fr-f-in'),
(37, '37', 'Indre-et-Loire', 'indre-et-loire', 'I536346', 'fr-f-il'),
(38, '38', 'IsÃ¨re', 'isere', 'I260', 'fr-v-is'),
(39, '39', 'Jura', 'jura', 'J600', 'fr-i-ju'),
(40, '40', 'Landes', 'landes', 'L532', 'fr-b-ld'),
(41, '41', 'Loir-et-Cher', 'loir-et-cher', 'L6326', 'fr-f-lc'),
(42, '42', 'Loire', 'loire', 'L600', 'fr-v-lr'),
(43, '43', 'Haute-Loire', 'haute-loire', 'H346', 'fr-c-hl'),
(44, '44', 'Loire-Atlantique', 'loire-atlantique', 'L634532', 'fr-r-la'),
(45, '45', 'Loiret', 'loiret', 'L630', 'fr-f-lt'),
(46, '46', 'Lot', 'lot', 'L300', 'fr-n-lo'),
(47, '47', 'Lot-et-Garonne', 'lot-et-garonne', 'L3265', 'fr-b-lg'),
(48, '48', 'LozÃ¨re', 'lozere', 'L260', 'fr-k-lz'),
(49, '49', 'Maine-et-Loire', 'maine-et-loire', 'M346', 'fr-r-ml'),
(50, '50', 'Manche', 'manche', 'M200', 'fr-p-mh'),
(51, '51', 'Marne', 'marne', 'M650', 'fr-g-mr'),
(52, '52', 'Haute-Marne', 'haute-marne', 'H3565', 'fr-g-hm'),
(53, '53', 'Mayenne', 'mayenne', 'M000', 'fr-r-my'),
(54, '54', 'Meurthe-et-Moselle', 'meurthe-et-moselle', 'M63524', 'fr-m-mm'),
(55, '55', 'Meuse', 'meuse', 'M200', 'fr-m-ms'),
(56, '56', 'Morbihan', 'morbihan', 'M615', 'fr-e-mb'),
(57, '57', 'Moselle', 'moselle', 'M240', 'fr-m-mo'),
(58, '58', 'NiÃ¨vre', 'nievre', 'N160', 'fr-d-ni'),
(59, '59', 'Nord', 'nord', 'N630', 'fr-o-no'),
(60, '60', 'Oise', 'oise', 'O200', 'fr-s-oi'),
(61, '61', 'Orne', 'orne', 'O650', 'fr-p-or'),
(62, '62', 'Pas-de-Calais', 'pas-de-calais', 'P23242', 'fr-o-pc'),
(63, '63', 'Puy-de-DÃ´me', 'puy-de-dome', 'P350', 'fr-c-pd'),
(64, '64', 'PyrÃ©nÃ©es-Atlantiques', 'pyrenees-atlantiques', 'P65234532', 'fr-b-pa'),
(65, '65', 'Hautes-PyrÃ©nÃ©es', 'hautes-pyrenees', 'H321652', 'fr-n-hp'),
(66, '66', 'PyrÃ©nÃ©es-Orientales', 'pyrenees-orientales', 'P65265342', 'fr-k-po'),
(67, '67', 'Bas-Rhin', 'bas-rhin', 'B265', 'fr-a-br'),
(68, '68', 'Haut-Rhin', 'haut-rhin', 'H365', 'fr-a-hr'),
(69, '69', 'RhÃ´ne', 'rhone', 'R500', 'fr-v-rh'),
(70, '70', 'Haute-SaÃ´ne', 'haute-saone', 'H325', 'fr-i-hn'),
(71, '71', 'SaÃ´ne-et-Loire', 'saone-et-loire', 'S5346', 'fr-d-sl'),
(72, '72', 'Sarthe', 'sarthe', 'S630', 'fr-r-st'),
(73, '73', 'Savoie', 'savoie', 'S100', 'fr-v-sv'),
(74, '74', 'Haute-Savoie', 'haute-savoie', 'H321', 'fr-v-hs'),
(75, '75', 'Paris', 'paris', 'P620', 'fr-j-vp'),
(76, '76', 'Seine-Maritime', 'seine-maritime', 'S5635', 'fr-q-sm'),
(77, '77', 'Seine-et-Marne', 'seine-et-marne', 'S53565', 'fr-j-se'),
(78, '78', 'Yvelines', 'yvelines', 'Y1452', 'fr-j-yv'),
(79, '79', 'Deux-SÃ¨vres', 'deux-sevres', 'D2162', 'fr-t-ds'),
(80, '80', 'Somme', 'somme', 'S500', 'fr-s-so'),
(81, '81', 'Tarn', 'tarn', 'T650', 'fr-n-ta'),
(82, '82', 'Tarn-et-Garonne', 'tarn-et-garonne', 'T653265', 'fr-n-tg'),
(83, '83', 'Var', 'var', 'V600', 'fr-u-vr'),
(84, '84', 'Vaucluse', 'vaucluse', 'V242', 'fr-u-vc'),
(85, '85', 'VendÃ©e', 'vendee', 'V530', 'fr-r-vd'),
(86, '86', 'Vienne', 'vienne', 'V500', 'fr-t-vn'),
(87, '87', 'Haute-Vienne', 'haute-vienne', 'H315', 'fr-l-hv'),
(88, '88', 'Vosges', 'vosges', 'V200', 'fr-m-vg'),
(89, '89', 'Yonne', 'yonne', 'Y500', 'fr-d-yo'),
(90, '90', 'Territoire de Belfort', 'territoire-de-belfort', 'T636314163', 'fr-i-tb'),
(91, '91', 'Essonne', 'essonne', 'E250', 'fr-j-es'),
(92, '92', 'Hauts-de-Seine', 'hauts-de-seine', 'H32325', 'fr-j-hd'),
(93, '93', 'Seine-Saint-Denis', 'seine-saint-denis', 'S525352', 'fr-j-ss'),
(94, '94', 'Val-de-Marne', 'val-de-marne', 'V43565', 'fr-j-vm'),
(95, '95', 'Val-d''oise', 'val-doise', 'V432', 'fr-j-vo'),
(971, '971', 'Guadeloupe', 'guadeloupe', 'G341', 'fr-gp-gp'),
(972, '972', 'Martinique', 'martinique', 'M6352', 'fr-mq-mq'),
(973, '973', 'Guyane', 'guyane', 'G500', 'fr-gf-gf'),
(974, '974', 'RÃ©union', 'reunion', 'R500', 'fr-re-re'),
(976, '976', 'Mayotte', 'mayotte', 'M300', 'fr-yt-yt'),
(1001, '2A', 'Corse-du-sud', 'corse-du-sud', 'C62323', 'fr-h-cs'),
(1002, '2B', 'Haute-corse', 'haute-corse', 'H3262', 'fr-h-hc');

--
-- Index pour les tables exportÃ©es
--

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportÃ©es
--

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1003;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
