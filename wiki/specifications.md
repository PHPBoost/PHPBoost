# Wiki 2 Specs

## Objectif
Passer le module wiki en mvc (renommé en Docsheet le temps des travaux)

## Spec
- items
    titre | catégorie | contenu | contenu court | statut qualité | renommage de l'auteur | vignette | mots clés | sources | publication différée | Nature de modification
- catégories 
richCategories avec ajout des droits de gestion des archives  

## Fonctionnement
- Page d'accueil en catégories/sous-catégories
- Explorateur sur le modèle de la page d'accueil de pages

- Page de réagencements de l'ordre des items dans une catégorie (accessible si 2 items min)

- création de la table des matières à partir des balises [title/] du contenu  seulement si contenu contient des titres  
    => pouvoir récupérer le html (couleur, icônes, etc) dans les titres de la table des matières et du contenu
    - Envoyer la table des matières dans une colonne latérale
        - dans le menu gauche si colonne gauche et droite sont présentes
        - dans colonne droite si seulement colonne droite présente
        - création de la colonne gauche si aucune colonne présente
    - Pushmenu si position fixe dans la config est activée + bouton clignotant fixe pour ouvrir le menu

- création d'un nouveau contenu séparé 
    - à la création un item ou d'une contribution
    - à l'édition un item déjà publié
        - simple mise à jour du contenu courant quand on édite un item non publié => évite d'avoir x contenus inutiles pour un item
        - édition multiple d'une contribution avant publication
        - passer un item déjà publié en brouillon pour pouvoir valider +rs fois avant revalidation définitive

- statut d'un item 
    - aucun | qualité | incomplet | en cours | à refaire(obsolète) | contesté || personnalisé + textarea

# Item
+ ~~page d'accueil en catégories/sous-catégories~~
+ ~~page explorateur~~
+ ~~page Mes fiches~~
+ ~~page Fiches en attente~~
+ ~~page Gestion de l'ordre d'affichage des items dans une catégorie~~

+ table des matières
    + ~~table des matières dans colonnes laterales~~
    + ~~table des matières en position fixe~~
+ création
    + categories
    + ~~item~~
        + ~~id~~
        + ~~id_category~~
        + ~~title~~
        + ~~rewrited_title~~
        + ~~i_order~~
        + ~~publication (published, start, end)~~
        + ~~creation_date~~
        + ~~views_number~~
        + 
        + ~~contributions~~
        + ~~keywords~~
    + contenu
        + ~~content_id (id du contenu)~~
        + ~~item_id (relation avec l'item)~~
        + ~~summary (résumé)~~
        + ~~active_content (1 = contenu affiché, 0 = contenu archivé)~~
        + ~~content~~
        + ~~thumbnail~~
        + ~~author_user_id~~
        + ~~author_custom_name~~
        + ~~update~~
        + ~~change_reason~~
        + ~~content_level~~
            + ~~custom_level (si content_level = autre)~~
        + ~~sources~~
        + 
        + ~~simple mise à jour du contenu d'un item en brouillon~~
+ historique
    - général (pas nécessaire car possibilité via menu feed)
    + ~~particulier~~
        + ~~ellipsis sur le change reason~~
+ ~~suppression d'un item = suppression des relations (+rs contenus, favoris, historique)~~
+ ~~recherche~~
+ ~~favoris~~
    + ~~bouton track/untrack dans l'item~~
    + ~~page liste des favoris~~

# Mini module
~~Menu dépliant liste complète des catégories/items~~

# Bugs
## Mineurs
- la date de mise à jour s'enregistre à la création
- Erreur non bloquante à la première install  
`invalid query. (ERRNO 1146) Table 'db_name.prefix_' doesn't exist`  
`query: SELECT * FROM prefix_ ORDER BY id_parent, c_order`

## Majeurs
- update 5.2 6.0  
- update 6.0.0 6.0.1  
cf erreur non bloquante de `bugs mineurs`
