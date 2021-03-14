<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 14
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

$lang['module.title'] = 'Multimédia';

$lang['items'] = 'fichiers';
$lang['item'] = 'fichier';

$lang['media.actions.add'] = 'Ajouter un fichier';
$lang['media.manage'] = 'Gérer les fichiers';
$lang['media.hide.file'] = 'Cacher le fichier';

// Categories
$lang['media.content.type'] = 'Types de fichiers autorisés';
$lang['media.content.type.music.and.video'] = 'Musique & Vidéo';
$lang['media.content.type.music'] = 'Musique';
$lang['media.content.type.video'] = 'Vidéo';

// Action
$lang['media.add.item'] = 'Ajouter un fichier';
$lang['media.delete.item'] = 'Supprimer un fichier';
$lang['media.edit.item'] = 'Éditer un fichier';
$lang['media.approval'] = 'Approuver';
$lang['media.description'] = 'Description du fichier';
$lang['media.moderation'] = 'Modération';
$lang['media.title'] = 'Titre du fichier';
$lang['media.file.url'] = 'Lien du fichier';
$lang['media.poster'] = 'Affiche de la vidéo';
$lang['media.height'] = 'Hauteur de la vidéo';
$lang['media.width'] = 'Largeur de la vidéo';
$lang['media.require.title'] = 'Vous devez donner un titre à ce fichier !';
$lang['media.require.file.url'] = 'Vous devez renseigner le lien de votre fichier !';
$lang['media.additional.contribution'] = 'Complément de contribution';
$lang['media.additional.contribution.description'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier). Ce champ est facultatif.';
$lang['media.contribution'] = 'Proposer un fichier';
$lang['media.contribution.notice'] = '
    Vous n\'êtes pas autorisé à ajouter un fichier, cependant vous pouvez en proposer un.
    <span class="error text-strong">La modification est possible tant que la contribution n\'a pas été approuvée.
    </span> Votre contribution suivra le parcours classique et sera traitée dans la panneau de contribution de PHPBoost.
    Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.
';

// Moderation
$lang['media.all.files'] = 'Tous les fichiers';
$lang['media.confirm.delete.all.files'] = 'Cette action supprimera DÉFINITIVEMENT tous les fichiers sélectionnés !';
$lang['media.display.files'] = 'Afficher les fichiers';
$lang['media.filter'] = 'Filtre';
$lang['media.include.sub.categories'] = ', inclure les sous-catégories:';
$lang['media.visible'] = 'Approuvés';
$lang['media.invisible'] = 'Masqués';
$lang['media.disapproved'] = 'Désapprouvés';
$lang['media.disapproved.description'] = 'Fichier désapprouvé';
$lang['media.invisible.description'] = 'Fichier approuvé mais masqué';
$lang['media.visible.description'] = 'Fichier approuvé et visible';

// Configuration
$lang['config.max.video.width'] = 'Largeur maximale d\'une vidéo';
$lang['config.max.video.height'] = 'Hauteur maximale d\'une vidéo';
$lang['config.root.category.media.content.type'] = 'Types de fichiers autorisés dans la racine des fichiers';
$lang['config.constant.host'] = 'Hébergeurs de confiance';
$lang['config.constant.host.peertube'] = 'Peertube';
$lang['config.constant.host.peertube.desc'] = '<a href="https://joinpeertube.org">Joinpeertube.org</a> - <a href="https://instances.joinpeertube.org/instances/">Liste des instances</a>';

// SEO
$lang['media.seo.description.root'] = 'Tous les fichiers du site :site.';

// Message helper
$lang['e_mime_disable_media'] = 'Le type de fichier que vous souhaitez proposer est désactivé !';
$lang['e_mime_unknow_media'] = 'Impossible de déterminer le type de ce fichier !';
$lang['e_link_empty_media'] = 'Veuillez renseigner le lien de votre fichier !';
$lang['e_link_invalid_media'] = 'Veuillez renseigner un lien valide pour votre fichier !';
$lang['e_unexist_media'] = 'Le fichier demandé n\'existe pas !';
$lang['e.bad.url.odysee'] = '
    L\'url Odysee renseignée n\'est pas valide. <br />
    Dans l\'onglet <span class="pinned question">Partager</span> sous la vidéo, choisir une des deux url suivantes:
    <ul>
        <li><span class="pinned question">Intégrer ce contenu</span> / url fournie dans <span class="pinned question">Intégré</span></li>
        <li><span class="pinned question">Liens</span> / url fournie dans <span class="pinned question">Lien de téléchargement</span></li>
    </ul>
';
$lang['e.bad.url.peertube'] = 'L\'url PeerTube renseignée n\'est pas valide. Elle ne correspond pas à l\'url renseignée dans la configuration du module.';
?>
