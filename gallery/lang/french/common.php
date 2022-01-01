<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 01
 * @since       PHPBoost 4.1 - 2015 02 10
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                       French                     #
####################################################

$lang['gallery.module.title']        = 'Galerie';
$lang['gallery.config.module.title'] = 'Configuration du module Galerie';
$lang['gallery.random.items']        = 'Images aléatoires';
$lang['gallery.no.random.item']      = 'Aucune image aléatoires';

// Configuration
$lang['gallery.max.height']                   = 'Hauteur max des images';
$lang['gallery.max.height.clue']              = '600px par défaut';
$lang['gallery.max.width']                    = 'Largeur max des images';
$lang['gallery.max.width.clue']               = '800px par défaut';
$lang['gallery.thumbnails.max.height']        = 'Hauteur max des miniatures';
$lang['gallery.thumbnails.max.width']         = 'Largeur max miniatures';
$lang['gallery.thumbnails.max.size.clue']     = '150px par défaut';
$lang['gallery.weight.max']                   = 'Poids maximum des images';
$lang['gallery.weight.max.clue']              = '1024 par défaut';
$lang['gallery.thumbnails.quality']           = 'Qualité miniatures';
$lang['gallery.thumbnails.quality.clue']      = '80% par défaut';
$lang['gallery.items.per.row.clue']           = '4 par défaut - Images et catégories';
$lang['gallery.items.per.page']               = 'Nombre d\'images par page';
$lang['gallery.item.resizing.mode']           = 'Mode d\'agrandissement des images';
$lang['gallery.new.page']                     = 'Nouvelle page';
$lang['gallery.resizing']                     = 'Redimensionnement';
$lang['gallery.popup']                        = 'Popup';
$lang['gallery.popup.full.screen']            = 'Plein écran';
$lang['gallery.enable.title']                 = 'Activer les titres';
$lang['gallery.enable.title.clue']            = 'Nom de l\'image au-dessus de la miniature';
$lang['gallery.enable.contributor']           = 'Activer l\'affichage du contributeur';
$lang['gallery.enable.contributor.clue']      = 'Nom du contributeur de l\'image sous la miniature';
$lang['gallery.enable.views.counter']         = 'Activer le compteur de vues';
$lang['gallery.enable.views.counter.clue']    = 'Nombre de vues de l\'image sous la miniature';
$lang['gallery.enable.notes.number']          = 'Afficher le nombre de notes';
$lang['gallery.items.protection']             = 'Protection des images';
$lang['gallery.enable.logo']                  = 'Activer le logo';
$lang['gallery.enable.logo.clue']             = 'Incrustation sur l\'image';
$lang['gallery.logo.url']                     = 'Adresse du logo';
$lang['gallery.logo.url.clue']                = 'Mettre dans le dossier /gallery';
$lang['gallery.horizontal.distance']          = 'Distance horizontale';
$lang['gallery.vertical.distance']            = 'Distance verticale';
$lang['gallery.from.bottom.right.clue']       = 'Par rapport au coin inférieur droit';
$lang['gallery.logo.trans']                   = 'Transparence du logo';
$lang['gallery.logo.trans.clue']              = '40% par défaut, seulement pour les logos en jpg';
$lang['gallery.items.upload']                 = 'Upload des images';
$lang['gallery.members.items.number']         = 'Nombre d\'images maximum';
$lang['gallery.members.items.number.clue']    = 'Membres (illimité si invités autorisés)';
$lang['gallery.moderators.items.number']      = 'Nombre d\'images maximum';
$lang['gallery.moderators.items.number.clue'] = 'Modérateurs';
$lang['gallery.mini.module']                  = 'Mini module';
$lang['gallery.thumbnails.number']            = 'Nombre de miniatures';
$lang['gallery.scroll.speed']                 = 'Vitesse du défilement';
$lang['gallery.scroll.speed.clue']            = 'Vitesse max: 10';
$lang['gallery.scroll.type']                  = 'Type de défilement';
$lang['gallery.no.scroll']                    = 'Aucun défilement';
$lang['gallery.static.scroll']                = 'Défilement statique';
$lang['gallery.vertical.scroll']              = 'Défilement dynamique vertical';
$lang['gallery.horizontal.scroll']            = 'Défilement dynamique horizontal';
$lang['gallery.cache']                        = 'Cache des miniatures';
$lang['gallery.cache.clue']                   = 'Régénération des miniatures (définitif !)<br />Vide le cache en cas de modification des configurations des miniatures, et recompte le nombre d\'images par catégories.';
    // add
$lang['gallery.upload.items']            = 'Uploader des images';
$lang['gallery.server.item']             = 'Images disponibles sur le serveur';
$lang['gallery.select.all.items']        = 'Sélectionner toutes les images';
$lang['gallery.deselect.all.items']      = 'Désélectionner toutes les images';
$lang['gallery.category.selection']      = 'Catégorie de toutes les images sélectionnées';
$lang['gallery.category.selection.clue'] = 'Permet de changer la catégorie pour toutes les images sélectionnées';
    // manager
$lang['gallery.items.in.category']          = 'Images dans la catégorie';
$lang['gallery.category.items.number.clue'] = 'Nombre d\'images<br />(<em>dont cachées</em>)';

// Errors
$lang['e_no_gd']                = 'Galerie -> Librairie GD non chargée';
$lang['e_unabled_create_pics']  = 'Galerie -> Echec création image';
$lang['e_no_graphic_support']   = 'Galerie -> Pas de support graphique avec PHP sur ce serveur';
$lang['e_no_getimagesize']      = 'Galerie -> Fonction getimagesize() non supportée, contactez votre hébergeur';
$lang['e_unsupported_format']   = 'Galerie -> Format image non supporté (jpg, gif, png, webp uniquement)';
$lang['e_unabled_incrust_logo'] = 'Galerie -> Incrustation du logo impossible, désactivez le dans la configuration de la galerie';
$lang['e_error_resize']         = 'Galerie -> Erreur redimensionnement';
$lang['e_unable_display_pics']  = 'Galerie -> Impossible d\'afficher l\'image !';
$lang['e_delete_thumbnails']    = 'Galerie -> Suppression des miniatures impossible';
$lang['e_error_img']            = 'Erreur image';
$lang['e_unexist_img']          = 'L\'image que vous demandez n\'existe pas';

// Labels
$lang['gallery.item']         = 'image';
$lang['gallery.items']        = 'images';
$lang['gallery.date.added']   = 'Ajoutée le';
$lang['gallery.thumbnails']   = 'Miniatures';
$lang['gallery.upload.limit'] = 'Limite d\'upload';

// Message helper
$lang['gallery.warning.success.upload'] = 'Vos images ont bien été enregistrées !';

// Notices
$lang['gallery.no.ftp.item'] = 'Aucune image supplémentaire présente sur le serveur';

// Tree links
$lang['gallery.add.items']  = 'Ajouter des images';
$lang['gallery.management'] = 'Gestion des images';

// Tools menu
$lang['gallery.top.views'] = 'Les plus vues';
$lang['gallery.top.rated'] = 'Les mieux notées';

// S.E.O.
$lang['gallery.seo.description.root'] = 'Toutes les images de la galerie du site :site.';

// Warnings
$lang['gallery.warning.height']              = 'Veuillez entrer une hauteur maximale pour les miniatures !';
$lang['gallery.warning.height.max']          = 'Veuillez entrer une hauteur maximale pour les images !';
$lang['gallery.warning.width.max']           = 'Veuillez entrer une largeur maximale pour les images !';
$lang['gallery.warning.width']               = 'Veuillez entrer une largeur maximale pour les miniatures !';
$lang['gallery.warning.weight.max']          = 'Veuillez entrez un poids maximum pour l\'image !';
$lang['gallery.warning.categories.per.page'] = 'Veuillez entrer le nombre de catégories par page !';
$lang['gallery.warning.row']                 = 'Veuillez entrer un nombre de colonne(s) pour la galerie !';
$lang['gallery.warning.items.per.page']      = 'Veuillez entrer le nombre d\'images par page !';
$lang['gallery.warning.quality']             = 'Veuillez entrer une qualité pour les miniatures !';
?>
