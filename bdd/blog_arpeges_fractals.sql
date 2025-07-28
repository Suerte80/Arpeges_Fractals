-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql
-- Généré le : lun. 28 juil. 2025 à 08:12
-- Version du serveur : 8.0.43
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_arpeges_fractals`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL COMMENT 'Id de l''article',
  `parution_date` date NOT NULL COMMENT 'Date de parution de l''article.',
  `views` int NOT NULL DEFAULT '0' COMMENT 'Nombre d''impression de l''article.',
  `id_image_pres` int NOT NULL COMMENT 'Id de l''image de présentation.',
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Titre de l''article.',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Description de l''article.',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Contenu de l''article.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `parution_date`, `views`, `id_image_pres`, `title`, `description`, `content`) VALUES
(1, '2025-03-13', 250, 1, 'À la découverte du math rock', 'Une introduction aux riffs angulaires et mesures impaires.', 'Le math rock se caractérise par des rythmes asymétriques, des textures sonores complexes et une sensibilité géométrique. Cet article explore ses origines et son influence actuelle.'),
(2, '2025-03-25', 0, 1, 'LITE : précision japonaise', 'Focus sur un pilier du genre instrumental.', 'Le groupe LITE incarne la virtuosité technique et la discipline du math rock japonais, mêlant structures rigoureuses et improvisation subtile.'),
(3, '2025-04-23', 0, 1, 'Scène émergente française', 'Qui sont les groupes qui cassent les codes en France ?', 'De Lyon à Rennes, la scène math rock française prend de l’ampleur. Nous avons rencontré les acteurs d’un son libre et complexe.'),
(4, '2025-04-15', 0, 1, 'Math rock & jazz fusion', 'Un lien inattendu mais naturel.', 'Des groupes comme Jizue ou Mouse on the Keys brouillent les frontières entre jazz contemporain et math rock. Décryptage d’un croisement élégant.'),
(5, '2025-07-06', 250, 1, 'La guitare comme fractale', 'Quand les arpèges deviennent géométriques.', 'Le math rock transforme la guitare en outil mathématique. Voici comment certaines structures évoquent des motifs fractals.'),
(6, '2025-06-08', 0, 1, 'Interview : tricot', 'Entretien exclusif avec le groupe japonais post-rock/math rock féminin.', 'tricot, groupe à la frontière du chaos et de la pop, nous parle de leur approche expérimentale unique du songwriting.'),
(7, '2025-02-05', 250, 1, 'Top 5 albums 2024', 'Notre sélection incontournable de l’année', 'De Covet à Delta Sleep, voici les albums qui ont marqué 2024 dans l’univers mathy et instrumental.'),
(8, '2025-06-04', 0, 1, 'Signature rythmique : le 7/8 expliqué', 'Un guide pour débuter dans les mesures impaires.', 'Le 7/8 est omniprésent dans le math rock. Apprenez à le sentir, l\'écrire et le jouer dans ce tutoriel illustré.'),
(9, '2025-05-06', 0, 1, 'Musique et architecture', 'Quand les sons dessinent des formes', 'Les structures musicales du math rock rappellent souvent celles de l’architecture contemporaine. Coïncidence ?'),
(10, '2025-02-26', 0, 1, 'Les femmes dans le math rock', 'Une visibilité croissante mais encore fragile.', 'De tricot à Yvette Young, retour sur les figures féminines qui façonnent un genre encore trop masculin.'),
(11, '2025-05-20', 0, 1, 'Effet pédale : math & reverb', 'Un arsenal sonore complexe.', 'Les effets utilisés par les musiciens math rock ajoutent une nouvelle dimension rythmique et spatiale. Exploration technique.'),
(12, '2025-03-07', 0, 1, 'Le DIY dans la scène underground', 'Concerts en garage, auto-prod et solidarité', 'Beaucoup de groupes math rock adoptent un fonctionnement DIY. Voici pourquoi (et comment) cela nourrit la créativité.'),
(13, '2025-04-10', 0, 1, 'Pourquoi tant de batteurs brillants ?', 'Une école de rigueur et d’audace', 'Le math rock est un paradis pour les batteurs techniques. Voici comment ils construisent des grooves impossibles.'),
(15, '2025-04-02', 0, 1, 'Tournée 2025 : dates annoncées', 'Découvrez les prochaines scènes math rock européennes.', 'Une vingtaine de dates sont prévues entre Berlin, Paris, et Barcelone pour la scène alternative math/post-rock.'),
(17, '2025-04-29', 0, 1, 'Construire un riff irrégulier', 'Guide d’écriture pour guitaristes curieux', 'Pas besoin d’être un mathématicien : l’irrégularité peut être intuitive. Voici comment structurer un riff original.'),
(20, '2025-06-12', 0, 1, 'Future of math rock', 'Fusion avec l’IA et l’interaction', 'Des projets mêlant codage en temps réel, IA et math rock commencent à émerger. Le futur est fractal.');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int NOT NULL,
  `image_filepath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `image_filepath`, `alt`, `uploaded_at`) VALUES
(1, 'images/articles/article.png', 'Image de l\'article de base.', '2025-07-24 14:36:55');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','moderator','admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `email_verified` tinyint(1) DEFAULT '0',
  `token_verification` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_avatar` int DEFAULT NULL,
  `bio` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `created_at`, `last_login`, `role`, `email_verified`, `token_verification`, `reset_token`, `id_avatar`, `bio`) VALUES
(14, 'Alexis', 'Courtin', 'alexiscourtin3@gmail.com', 'suerte80', '$2y$10$iEZfgs2YJiCm89yaSoyifeAX9ereiHcP4h/2U870BeBPa.HFccypW', '2025-07-28 08:10:36', '2025-07-28 08:10:36', 'user', 0, 'f98219010ca1921573fba2c3314bfac9236dca70691c57ccf1', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_image_pres` (`id_image_pres`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `image_file` (`image_filepath`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_avatar` (`id_avatar`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Id de l''article', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`id_image_pres`) REFERENCES `images` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_avatar` FOREIGN KEY (`id_avatar`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
