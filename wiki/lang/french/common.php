<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 01
 * @since       PHPBoost 1.6 - 2007 10 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

####################################################
#                       French                     #
####################################################

$lang['wiki.module.title'] = 'Wiki';
$lang['wiki.menu.title']   = 'Arborescence du wiki';
$lang['wiki.explorer']     = 'Explorateur';
$lang['wiki.overview']     = 'Sommaire';

// TreeLinks
$lang['item']             = 'fiche';
$lang['items']            = 'fiches';
$lang['an.item']          = 'une fiche';
$lang['the.item']         = 'la fiche';
$lang['items.reorder']    = 'Réorganiser les fiches';
$lang['items.reordering'] = 'Réorganisation des fiches';

// Table of contents
$lang['wiki.contents.table']        = 'Table des matières';
$lang['wiki.name']                  = 'Nom du wiki';
$lang['wiki.sticky.contents.table'] = 'Afficher la table des matières en position fixe';

// Titles
$lang['wiki.root']             = 'Sans catégorie';
$lang['wiki.add.item']         = 'Ajouter une fiche';
$lang['wiki.edit.item']        = 'Modifier une fiche';
$lang['wiki.duplicate.item']   = 'Dupliquer une fiche';
$lang['wiki.my.items']         = 'Mes fiches';
$lang['wiki.my.tracked']       = 'Mes favoris';
$lang['wiki.member.items']     = 'Fiches publiées par';
$lang['wiki.pending.items']    = 'Fiches en attente';
$lang['wiki.filter.items']     = 'Filtrer les fiches';
$lang['wiki.items.management'] = 'Gestion des fiches';
$lang['wiki.item.history']     = 'Historique de la fiche';
$lang['wiki.restore.item']     = 'Restaurer cette version';
$lang['wiki.confirm.restore']  = 'Êtes-vous sûr de vouloir restaurer cette version ?';
$lang['wiki.history.init']     = 'Initialisation';
$lang['wiki.current.version']  = 'Version courrante';
$lang['wiki.delete.version']   = 'Supprimer cette version';
$lang['wiki.archive']          = 'Archive';
$lang['wiki.archived.item']    = 'Consulter';
$lang['wiki.archived.content'] = 'Cette fiche a été mise à jour, vous consultez ici une archive !';
$lang['wiki.track']            = 'Suivre cette fiche';
$lang['wiki.untrack']          = 'Ne plus suivre cette fiche';

// Levels
$lang['wiki.level'] = 'Niveau de confiance';

$lang['wiki.level.trust']  = 'Contenu de confiance';
$lang['wiki.level.claim']  = 'Contenu contesté';
$lang['wiki.level.redo']   = 'Contenu à refaire';
$lang['wiki.level.sketch'] = 'Contenu incomplet';
$lang['wiki.level.wip']    = 'Contenu en construction';

$lang['wiki.level.trust.message']  = 'Cette fiche est de grande qualité, elle est complète et fiable.';
$lang['wiki.level.claim.message']  = 'Cette fiche a été discutée et son contenu ne paraît pas correct. Vous pouvez éventuellement consulter les discussions à ce propos et peut-être y apporter vos connaissances.';
$lang['wiki.level.redo.message']   = 'Cette fiche est à refaire, son contenu n\'est pas très fiable.';
$lang['wiki.level.sketch.message'] = 'Cette fiche manque de sources.<br />Vos connaissances sont les bienvenues afin de le compléter.';
$lang['wiki.level.wip.message']    = 'Cette fiche est en cours de travaux, des modifications sont en cours de réalisation, n`hésitez pas à revenir plus tard la consulter.';

$lang['wiki.level.custom']         = 'Niveau personnalisé';
$lang['wiki.level.custom.content'] = 'Description du niveau personnalisé';

// Form
$lang['wiki.change.reason']       = 'Nature de la modification';
$lang['wiki.suggestions.number']  = 'Nombre d\'éléments suggérés à afficher';
$lang['wiki.homepage']            = 'Choisir le type de page d\'accueil';
$lang['wiki.display.description'] = 'Afficher la description des sous-catégories';
$lang['wiki.menu.configuration']  = 'Configuration du mini module';
$lang['wiki.menu.title.name']     = 'Nom du mini module';
$lang['wiki.config.root']         = 'Racine du wiki (Catégories et items de la racine)';
$lang['wiki.config.explorer']     = 'Explorateur (Toutes les catégories et items)';
$lang['wiki.config.overview']     = 'Sommaire (Toutes les catégories)';

// Authorizations
$lang['wiki.manage.archives'] = 'Autorisation de gérer les archives';

// SEO
$lang['wiki.seo.description.root']    = 'Toutes les fiches du site :site.';
$lang['wiki.seo.description.tag']     = 'Toutes les fiches sur le sujet :subject.';
$lang['wiki.seo.description.pending'] = 'Toutes les fiches en attente.';
$lang['wiki.seo.description.member']  = 'Toutes les fiches de :author.';
$lang['wiki.seo.description.tracked'] = 'Toutes les fiches suivies de :author.';
$lang['wiki.seo.description.history'] = 'Historique de la fiche :item.';

// Messages helper
$lang['wiki.message.success.add']            = 'La fiche <b>:title</b> a été ajoutée';
$lang['wiki.message.success.edit']           = 'La fiche <b>:title</b> a été modifiée';
$lang['wiki.message.success.delete']         = 'La fiche <b>:title</b> a été supprimée';
$lang['wiki.message.success.delete.content'] = 'Le contenu :content de la fiche <b>:title</b> a été supprimé';
$lang['wiki.message.success.restore']        = 'Le contenu :content de la fiche <b>:title</b> a été restauré';
$lang['wiki.message.draft'] = '
    <div class="message-helper bgc warning">
        L\'édition d\'une fiche la place automatiquement en <b>brouillon</b>. Cela permet plusieurs validations sans en multiplier excessivement les archives.
        <br /><br />
        <p>Pensez à modifier le statut de publication en fin de travaux !</p>
    </div>
';
?>
