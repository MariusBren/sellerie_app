----- GUIDE D'INSTALLATION -----

**1) Cloner le dépot pour récupérer le projet :**
  `git clone https://github.com/MariusBren/sellerie_app`

**2) Se déplacer dans le projet pour pouvoir poursuivre l'installation :**
  `cd sellerie_app`

**3) Installer toutes les dépendances PHP avec composer :**
  `composer install`

**4) Si nécessaire, mettre à jour l'addresse de la base de donnée dans le fichier .env.**

**5) Générer la base de données du projet :**
  `console doctrine:database:create`

**6) Exécuter toutes les migrations pour mettre la BDD à jour :**
  `console doctrine:migrations:migrate`

**7) Peupler la BDD :**
  `console doctrine:fixtures:load`

**8) Déployer le serveur Symfony :**
  `symfony serve`

**9) Accéder au projet sur navigateur via l'addresse suivante :**
  `http://127.0.0.1:8000`
