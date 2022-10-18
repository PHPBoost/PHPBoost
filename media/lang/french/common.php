<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 24
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

$lang['media.module.title'] = 'Multimédia';

$lang['media.items']       = 'fichiers';
$lang['media.item']        = 'fichier';
$lang['media.add.item']    = 'Ajouter un fichier';
$lang['media.delete.item'] = 'Supprimer un fichier';
$lang['media.edit.item']   = 'Éditer un fichier';
$lang['media.hide.item']   = 'Cacher le fichier';

// Tree links
$lang['media.management']      = 'Gestion des fichiers';

// Categories
$lang['media.content.type']                 = 'Types de fichiers autorisés';
$lang['media.content.type.music.and.video'] = 'Musique & Vidéo';
$lang['media.content.type.music']           = 'Musique';
$lang['media.content.type.video']           = 'Vidéo';

// Action
$lang['media.file.url'] = 'Lien du fichier';
$lang['media.poster']   = 'Affiche de la vidéo';
$lang['media.height']   = 'Hauteur de la vidéo';
$lang['media.width']    = 'Largeur de la vidéo';

// Moderation
$lang['media.all.files']                = 'Tous les fichiers';
$lang['media.confirm.delete.all.files'] = 'Cette action supprimera DÉFINITIVEMENT tous les fichiers sélectionnés !';
$lang['media.display.files']            = 'Afficher les fichiers';
$lang['media.filter']                   = 'Filtre';
$lang['media.include.sub.categories']   = ', inclure les sous-catégories:';
$lang['media.visible']                  = 'Approuvés';
$lang['media.invisible']                = 'Masqués';
$lang['media.disapproved']              = 'Désapprouvés';
$lang['media.disapproved.description']  = 'Fichier désapprouvé';
$lang['media.invisible.description']    = 'Fichier approuvé mais masqué';
$lang['media.visible.description']      = 'Fichier approuvé et visible';

// Configuration
$lang['media.max.video.width']             = 'Largeur maximale d\'une vidéo';
$lang['media.max.video.height']            = 'Hauteur maximale d\'une vidéo';
$lang['media.root.content.type']           = 'Types de fichiers autorisés dans la racine des fichiers';
$lang['media.constant.host']               = 'Hébergeurs de confiance';
$lang['media.constant.host.peertube']      = 'Peertube';
$lang['media.constant.host.peertube.desc'] = '<a href="https://joinpeertube.org">Joinpeertube.org</a> - <a href="https://instances.joinpeertube.org/instances/">Liste des instances</a>';

// S.E.O.
$lang['media.seo.description.root'] = 'Tous les fichiers du site :site.';

// Message helper
$lang['e_mime_disable_media'] = 'Le type de fichier que vous souhaitez proposer est désactivé !';
$lang['e_mime_unknow_media']  = 'Impossible de déterminer le type de ce fichier !';
$lang['e_link_empty_media']   = 'Veuillez renseigner le lien de votre fichier !';
$lang['e_link_invalid_media'] = 'Veuillez renseigner un lien valide pour votre fichier !';
$lang['e_unexist_media']      = 'Le fichier demandé n\'existe pas !';
$lang['e_bad_url_odysee'] = '
    L\'url Odysee renseignée n\'est pas valide. <br />
    Dans l\'onglet <span class="pinned question">Partager</span> sous la vidéo, choisir une des deux url suivantes:
    <ul>
        <li><span class="pinned question">Intégrer ce contenu</span> / url fournie dans <span class="pinned question">Intégré</span></li>
        <li><span class="pinned question">Liens</span> / url fournie dans <span class="pinned question">Lien de téléchargement</span></li>
    </ul>
';
$lang['e_bad_url_peertube'] = 'L\'url PeerTube renseignée n\'est pas valide. Elle ne correspond pas à l\'url renseignée dans la configuration du module.';
?>
