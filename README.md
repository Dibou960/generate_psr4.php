# generate_psr4.php
Utilitaire PHP pour gérer les espaces de noms et les chargements automatiques.
Script de Gestion des Espaces de Noms PHP
Ce script PHP est conçu pour simplifier la gestion des espaces de noms dans un projet PHP. Il comprend deux fonctions principales : obtenirEspacesDeNomsAvecChemins et injecterPsr4.

Fonctions principales :
obtenirEspacesDeNomsAvecChemins
Cette fonction parcourt un répertoire spécifié à la recherche de fichiers PHP et récupère les espaces de noms associés avec leur chemin correspondant. Ces informations sont utiles pour organiser la structure des fichiers d'un projet et pour l'autoloading des classes.

injecterPsr4
Cette fonction permet d'ajouter ou de mettre à jour des entrées PSR-4 dans le fichier composer.json en fonction des espaces de noms et des chemins fournis. Cela facilite l'autoloading des classes dans un projet utilisant Composer.

Utilisation :
Téléchargez le script gestion_espaces_de_noms.php et placez-le dans le répertoire racine de votre projet PHP.
Exécutez la commande "php generate_psr4.php" depuis votre terminal, à la racine de votre projet PHP :

Dépendances :
Ce script nécessite PHP version 8.3 ou supérieure.

Licence :
Ce script est distribué sous la licence MIT. Veuillez consulter le fichier LICENSE.md pour plus d'informations.

Contributions :
Les contributions sont les bienvenues ! Si vous avez des idées d'amélioration, des corrections de bugs ou des fonctionnalités supplémentaires à ajouter, n'hésitez pas à ouvrir une issue ou à soumettre une pull request.
