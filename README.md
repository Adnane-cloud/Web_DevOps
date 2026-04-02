# Eventium 🎟️

**Eventium** est une plateforme moderne et premium de gestion d'événements, construite avec un framework PHP MVC léger et personnalisé. Elle propose une interface utilisateur en glassmorphisme époustouflante, des analyses en temps réel et des flux d'inscription aux événements fluides.

---

## ✨ Fonctionnalités Clés

### 👤 Pour les Utilisateurs
*   **Découverte d'Événements** : Parcourez les événements par catégorie avec une interface carrousel fluide ou recherchez instantanément.
*   **Inscription Simplifiée** : Inscription en un clic aux événements grâce à un système de jetons sécurisé.
*   **Billets Numériques** : Générez et téléchargez des **Invitations PDF** avec des **QR Codes** uniques pour la validation à l'entrée.
*   **Calendrier Interactif** : Vue d'ensemble visuelle des activités à venir utilisant FullCalendar.
*   **Preuve Sociale** : Notez et commentez les événements auxquels vous avez participé (système 5 étoiles).
*   **Gestion de Profil** : Suivez votre historique d'inscriptions et gérez les paramètres de votre compte.

### 🛡️ Pour les Administrateurs
*   **Centre de Commandement** : Un tableau de bord en glassmorphisme affichant des statistiques en temps réel, des graphiques de tendances (inscriptions/catégories) et des flux d'activités en direct.
*   **Gestion des Événements** : Capacités CRUD complètes pour les événements, y compris le téléchargement d'images et le contrôle du statut (Actif/Clôturé).
*   **Gestion des Catégories** : Organisez les événements en catégories visuelles.
*   **Suivi des Présences** : Surveillez les listes d'invités, consultez les profils utilisateurs détaillés et identifiez les événements les plus performants.
*   **Export de Données** : Exportez les données des événements au format CSV pour des rapports externes.

---

## 🛠️ Stack Technique

*   **Backend** : PHP 8.x (Architecture MVC Personnalisée, PDO)
*   **Base de Données** : MySQL
*   **Frontend** : 
    *   HTML5 / CSS3 (Sass non requis, CSS pur)
    *   **Bootstrap 5** (Grille & classes utilitaires)
    *   **Glassmorphisme** (`store.css` & `admin.css` personnalisés)
    *   **Animations** : Cercles flottants, dégradés fluides, effets de survol.
*   **Bibliothèques JavaScript** :
    *   [FullCalendar](https://fullcalendar.io/) (Visualisation d'événements)
    *   [Chart.js](https://www.chartjs.org/) (Analytique Admin)
*   **APIs Externes** :
    *   QR Server API (Génération de billets)
    *   UI Avatars (Placeholders pour profils utilisateurs)

---

## 🚀 Installation et Configuration

1.  **Cloner le Dépôt**
    ```bash
    git clone https://github.com/Abderrahmane-jacker/Project_web
    cd eventium
    ```

2.  **Configuration de la Base de Données**
    *   Créez une nouvelle base de données MySQL (ex : `eventium_db`).
    *   Importez le schéma logique et les données depuis le fichier `database.sql` situé à la racine.

3.  **Configuration**
    *   Ouvrez `app/config/database.php`.
    *   Mettez à jour les identifiants pour correspondre à votre environnement local :
        ```php
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'eventium_db');
        ```

4.  **Lancer l'Application**
    *   Servez l'application via Apache/Nginx ou le serveur intégré de PHP raciné dans le dossier `public` :
        ```bash
        php -S localhost:8000 -t public
        ```
    *   Visitez `http://localhost:8000` dans votre navigateur.

---

## 📂 Structure du Projet

```text
/app
    /config         # Configuration Base de données & App
    /controllers    # Couche Logique (Admin, Menu, Auth, etc.)
    /core           # Cœur du Framework (Routeur, Contrôleur de base)
    /views          # Templates HTML/PHP
/public
    /css            # Feuilles de style personnalisées (store.css, admin.css)
    /js             # Logique Frontend
    /uploads        # Contenu téléchargé par les utilisateurs
    index.php       # Point d'entrée
/routes             # Définitions des routes
database.sql        # Dump SQL principal
```

## 🎨 Philosophie du Design

Eventium adopte une esthétique "Minimaliste Premium" fortement inspirée par le langage de design d'Apple :
*   **Glassmorphisme** : Cartes et en-têtes translucides avec flou d'arrière-plan (`backdrop-filter`).
*   **Typography** : Utilise la pile de polices système (SF Pro, Roboto) pour une lisibilité épurée.
*   **Profondeur** : Ombres subtiles et superposition pour créer une hiérarchie.
*   **Mouvement** : Animations d'arrière-plan lentes et ambiantes (cercles "respirants") pour ajouter de la vie sans distraction.

---
*Fait avec ❤️ *
