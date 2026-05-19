📘 PeerSync
🧩 Description du projet

PeerSync est une plateforme web développée en PHP POO permettant de gérer un système d’entraide entre apprenants et tuteurs dans un bootcamp intensif.

L’objectif est de remplacer les échanges informels (Discord, messages non structurés) par un système centralisé permettant de :

gérer les demandes d’aide
suivre les sessions de tutorat
évaluer les tuteurs
mesurer l’impact de l’entraide
🎯 Objectifs
Centraliser les demandes d’aide des apprenants
Connecter les apprenants avec des tuteurs disponibles
Suivre le cycle complet d’une session (création → assignation → résolution)
Évaluer les tuteurs via un système de notation
Fournir des statistiques sur l’activité de la plateforme
👥 Rôles utilisateurs
🎓 Apprenant
Créer une demande d’aide
Suivre le statut de sa demande
Marquer une demande comme résolue
Noter le tuteur (1 à 5 étoiles)
👨‍🏫 Tuteur
Consulter les demandes en attente
Accepter une demande
Aider les apprenants
Gagner des points et badges
🛠️ Administrateur
Consulter les statistiques globales
Suivre les tuteurs les plus actifs
Analyser les technologies les plus demandées
⚙️ Technologies utilisées
PHP 8+ (POO)
MySQL
PDO
Architecture orientée objet
Repository Pattern
Enums PHP
HTML / TailwindCSS
🏗️ Architecture du projet
config/ → Connexion base de données
entities/ → Classes métier (User, HelpRequest, Review)
repositories/ → Accès base de données (PDO uniquement)
actions/ → Traitement des formulaires
pages/ → Interface utilisateur
enums/ → États (Status, Role)
🔄 Fonctionnement général
Un apprenant crée une demande d’aide
La demande apparaît sur le tableau des tuteurs
Un tuteur accepte la demande
Une session d’entraide est réalisée
L’apprenant marque la demande comme résolue
L’apprenant note le tuteur
Les statistiques sont mises à jour
📊 Base de données
Tables principales :
users → utilisateurs (apprenants, tuteurs, admin)
help_requests → demandes d’aide
reviews → évaluations des tuteurs
🧠 Concepts clés du projet
Programmation orientée objet (POO)
Encapsulation (propriétés privées)
Typage strict PHP
Hydratation d’objets
Séparation des responsabilités
Repository Pattern (SQL isolé)
🚀 Installation
git clone https://github.com/your-repo/peersync.git
cd peersync
Importer la base de données (sql/structure.sql)
Configurer la connexion dans config/Database.php
Lancer le projet sur un serveur local (XAMPP / WAMP)
📌 Statut du projet

🚧 Projet en cours de développement (Bootcamp ENAA)

👨‍💻 Auteur

Projet réalisé dans le cadre d’un bootcamp intensif de développement web.
