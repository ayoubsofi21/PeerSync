📘 PeerSync
🧩 1. Description du projet

PeerSync est une plateforme web développée en PHP POO permettant de gérer un système d’entraide entre apprenants et tuteurs dans un bootcamp intensif.

L’objectif est de remplacer les échanges informels (Discord, messages non structurés) par une plateforme centralisée et organisée pour :

gérer les demandes d’aide
suivre les sessions de tutorat
évaluer les tuteurs
mesurer l’impact de l’entraide
🎯 2. Objectifs
Centraliser les demandes des apprenants
Connecter apprenants et tuteurs disponibles
Suivre le cycle complet d’une session
Évaluer les tuteurs
Générer des statistiques d’activité
👥 3. Rôles utilisateurs
🎓 Apprenant
Créer une demande d’aide
Suivre le statut (Pending / Assigned / Resolved)
Marquer une demande comme résolue
Noter le tuteur (1 à 5 étoiles)
👨‍🏫 Tuteur
Voir les demandes en attente
Accepter une demande
Aider les apprenants
Gagner des points et badges
🛠️ Administrateur
Voir les statistiques globales
Suivre les tuteurs actifs
Analyser les technologies demandées
⚙️ 4. Technologies utilisées
PHP 8+ (POO)
MySQL
PDO
Architecture orientée objet
Repository Pattern
Enums PHP
HTML / TailwindCSS
🏗️ 5. Architecture du projet
config/ → Connexion base de données
entities/ → Classes métier (User, HelpRequest, Review)
repositories/ → Accès base de données (PDO uniquement)
actions/ → Traitement des formulaires
pages/ → Interface utilisateur
enums/ → États (Status, Role)
🔄 6. Fonctionnement général
Un apprenant crée une demande d’aide
La demande apparaît sur le tableau des tuteurs
Un tuteur accepte la demande
Une session d’entraide est réalisée
L’apprenant marque la demande comme résolue
L’apprenant note le tuteur
Les statistiques sont mises à jour
🗄️ 7. Base de données
Tables principales
users → apprenants, tuteurs, admins
help_requests → demandes d’aide
reviews → évaluations des tuteurs
🧠 8. Concepts clés
Programmation orientée objet (POO)
Encapsulation (private properties)
Typage strict
Hydratation d’objets
Séparation des responsabilités
Repository Pattern (SQL isolé)
🚀 9. Installation
git clone https://github.com/your-repo/peersync.git
cd peersync
Étapes :
Importer la base de données :
sql/structure.sql
Configurer la base :
config/Database.php
Lancer avec XAMPP / WAMP
📌 10. Statut du projet

🚧 Projet en cours de développement (ENAA Bootcamp)

👨‍💻 11. Auteur

Projet réalisé dans le cadre d’un bootcamp intensif de développement web.
