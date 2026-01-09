# Mediatekformation – back office
Le projet d’origine de ce dépôt, qui contient notamment la partie front office, est disponible à l’adresse suivante :  <br>
https://github.com/CNED-SLAM/mediatekformation  
<br>
Le README du dépôt d’origine présente les fonctionnalités initiales de l’application.

## Evolutions apportées à l'application Mediatekformation
Ce projet permet l'intégration de la partie back-office sécurisée, avec la gestion du contenu de la BDD, et la mise en place de fonctionnalités supplémentaires. <br>
Les évolutions apportées sont l'ajout d'un back office avec gestion de la bdd, la gestion des tests, la création de la documentation technique et le déploiement en production.

Les fonctionnalités suivantes ont ainsi été ajoutées sur le site :

- Mise en place d’un back office sécurisé par authentification :
<img width="1663" height="422" alt="image" src="https://github.com/user-attachments/assets/d6ecb5e6-aa72-423b-a0f1-351eef747675" />

- Gestion des formations (ajout, modification, suppression)
<img width="1765" height="1102" alt="image" src="https://github.com/user-attachments/assets/7091c48c-aed1-48d7-85cc-99c7e47fd28b" />

- Gestion des playlists (ajout, modification, suppression)
<img width="1633" height="1026" alt="image" src="https://github.com/user-attachments/assets/32c421db-94c9-431b-816c-7c9b557a7ca8" />

- Gestion des catégories (ajout et suppression)
<img width="1668" height="1168" alt="image" src="https://github.com/user-attachments/assets/14bb1a06-1313-4853-a81b-ebdc3f96d897" />

- Rectification du front office avec l’ajout de l'affichage du nombre de formations par playlist
- Gestion des tests
- Génération d’une documentation technique
- Déploiement et mise en place d’un déploiement continu

## Installation en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés.
- Télécharger le code et le placer dans le dossier www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes, se positionner à la racine du projet et exécuter la commande "composer install".<br>
- Importer le fichier mediatekformation.sql pour créer et remplir la BDD en local.<br>
- Configurer les variables d’environnement dans le fichier .env (connexion à la base de données).
- Lancer l’application à l’adresse : http://localhost/mediatekformation/public/index.php<br>

## Test de l’application en ligne

L’application est également accessible en ligne sur l'hébergeur AlwaysData, à l'adresse : https://projetsisis.alwaysdata.net/mediatekformation/<br>
La documentation technique est disponible à cette adresse : https://projetsisis.alwaysdata.net/mediatekformation/docs/index.html<br>
Les informations nécessaires pour accéder à la partie back office seront fournies dans la fiche de rendu.
