# 📘 PeerSync

## 🧩 Description du projet

PeerSync est une plateforme web développée en **PHP POO** permettant de gérer un système d’entraide entre apprenants et tuteurs dans un bootcamp intensif.

L’objectif est de remplacer les échanges informels (Discord, messages non structurés) par une plateforme centralisée et organisée pour :

- gérer les demandes d’aide
- suivre les sessions de tutorat
- évaluer les tuteurs
- mesurer l’impact de l’entraide

---

## 🎯 Objectifs

- Centraliser les demandes des apprenants
- Connecter apprenants et tuteurs disponibles
- Suivre le cycle complet d’une session
- Évaluer les tuteurs
- Générer des statistiques d’activité

---

## 👥 Rôles utilisateurs

### 🎓 Apprenant

- Créer une demande d’aide
- Suivre le statut (Pending / Assigned / Resolved)
- Marquer une demande comme résolue
- Noter le tuteur (1 à 5 étoiles)

### 👨‍🏫 Tuteur

- Voir les demandes en attente
- Accepter une demande
- Aider les apprenants
- Gagner des points et badges

### 🛠️ Administrateur

- Voir les statistiques globales
- Suivre les tuteurs actifs
- Analyser les technologies demandées

---

## ⚙️ Technologies utilisées

- PHP 8+ (POO)
- MySQL
- PDO
- Architecture orientée objet
- Repository Pattern
- Enums PHP
- HTML / TailwindCSS

---

## 🏗️ Architecture du projet

```txt
config/         → Connexion base de données
entities/       → Classes métier (User, HelpRequest, Review)
repositories/   → Accès base de données (PDO uniquement)
actions/        → Traitement des formulaires
pages/          → Interface utilisateur
enums/          → États (Status, Role)
```
