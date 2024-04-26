<?php
/*
* #Description: Ce script PHP contient deux fonctions utiles pour la gestion des espaces de noms dans un projet.
 #La première fonction, obtenirEspacesDeNomsAvecChemins, parcourt un répertoire à la recherche de fichiers PHP et récupère les espaces de noms associés avec leur chemin correspondant.
 #La seconde fonction, injecterPsr4, permet d'ajouter ou de mettre à jour des entrées PSR-4 dans le fichier composer.json en fonction des espaces de noms et des chemins fournis.
 * 
 */

/**
 * Cette fonction parcourt un répertoire à la recherche de fichiers PHP et récupère les espaces de noms associés avec leur chemin correspondant.
 *
 * @param string $repertoire Le répertoire à parcourir.
 * @param string $cheminBase Le chemin de base à utiliser pour les espaces de noms.
 * @return array Un tableau associatif contenant les espaces de noms comme clés et les chemins correspondants comme valeurs.
 * @author Ibrahima Dieng
 */

function obtenirEspacesDeNomsAvecChemins($repertoire, $cheminBase)
{
    $espacesDeNomsAvecChemins = [];
    $iterateur = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($repertoire));

    foreach ($iterateur as $fichier) {
        if ($fichier->isDir()) {
            continue;
        }

        # Exclure les fichiers qui ne sont pas des fichiers PHP
        if ($fichier->getExtension() !== 'php') {
            continue;
        }

        $contenu = file_get_contents($fichier->getPathname());

        # Trouver l'espace de noms dans le fichier
        if (preg_match('/namespace\s+([^;]+);/m', $contenu, $correspondances)) {
            $espaceDeNom = trim($correspondances[1]);
            # Concaténer le chemin de base avec l'espace de noms et le stocker dans le tableau associatif
            $espacesDeNomsAvecChemins["$espaceDeNom\\"] = "$cheminBase" . str_replace('\\', '/', $espaceDeNom) . '/';
        }
    }
    return $espacesDeNomsAvecChemins;
}


function injecterPsr4($espaceDeNom, $chemin)
{
    # Charger le contenu du fichier composer.json
    $fichierComposer = file_get_contents('composer.json');

    # Convertir le contenu JSON en tableau associatif
    $donneesComposer = json_decode($fichierComposer, true);

    # Vérifier si la clé "autoload" existe déjà dans le fichier composer.json
    if (!isset($donneesComposer['autoload'])) {
        $donneesComposer['autoload'] = [];
    }

    # Vérifier si la clé "psr-4" existe déjà dans le tableau "autoload"
    if (!isset($donneesComposer['autoload']['psr-4'])) {
        $donneesComposer['autoload']['psr-4'] = [];
    }

    # Vérifier si la clé "require" est un tableau vide
    if (empty($donneesComposer['require']) || !is_array($donneesComposer['require'])) {
        # Remplacer le tableau vide par un objet vide
        $donneesComposer['require'] = (object) [];
    }

    # Vérifier si l'espace de nom n'existe pas déjà dans le fichier composer.json
    if (!isset($donneesComposer['autoload']['psr-4'][$espaceDeNom])) {
        # Ajouter l'espace de nom et son chemin au tableau psr-4
        $donneesComposer['autoload']['psr-4'][$espaceDeNom] = $chemin;
    } elseif ($donneesComposer['autoload']['psr-4'][$espaceDeNom] !== $chemin) {
        # Si l'espace de nom existe mais avec un chemin différent, avertir l'utilisateur
        echo "Attention: L'espace de nom '$espaceDeNom' existe déjà avec un chemin différent.\n";
        echo "Ancien chemin: " . $donneesComposer['autoload']['psr-4'][$espaceDeNom] . "\n";
        echo "Nouveau chemin: $chemin\n";
    }

    # Convertir le tableau associatif en JSON
    $nouveauContenu = json_encode($donneesComposer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    # Écrire le nouveau contenu dans le fichier composer.json
    file_put_contents('composer.json', $nouveauContenu);
}


# Obtenir les espaces de noms avec les chemins pour le backend et le frontend
$espacesDeNomsBackend = obtenirEspacesDeNomsAvecChemins('Backend/', '');
$espacesDeNomsBackend = obtenirEspacesDeNomsAvecChemins('core/', '');

# Fusionner les deux tableaux en un seul
$espacesDeNomsAvecChemins = $espacesDeNomsBackend;

# Formater les données pour obtenir la structure souhaitée
$donneesFormatees = [];
foreach ($espacesDeNomsAvecChemins as $espaceDeNom => $chemin) {
    $espaceDeNomFormatte = str_replace('\\', '\\\\', $espaceDeNom);
    $donneesFormatees["\"$espaceDeNomFormatte\\\\\":"] = "\"$chemin\",";
}
foreach ($espacesDeNomsAvecChemins as $espaceDeNom => $chemin) {
    injecterPsr4($espaceDeNom, $chemin);
}


?>
