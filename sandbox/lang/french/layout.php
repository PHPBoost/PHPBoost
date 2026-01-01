<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 05
 * @since       PHPBoost 5.2 - 2020 05 22
*/

####################################################
#                    French                        #
####################################################

// Generics
$lang['sandbox.layout.titles']    = 'Titres';
$lang['sandbox.layout.title']     = 'Titre';
$lang['sandbox.layout.title.sub'] = 'Sous titre';
$lang['sandbox.layout.items']     = 'Éléments';
$lang['sandbox.layout.item']      = 'Élément';

// Grid
$lang['sandbox.layout.grid']      = 'Grille d\'affichage';
$lang['sandbox.layout.grid.clue'] = '
    <p>Il y a 3 types d\'affichage, définis sur l\'élément parent :
        <ul>
            <li>Affichage libre : <code>.cell-flex.cell-inline</code></li>
            <li>Affichage en mosaïc : <code>.cell-flex.cell-columns-{1 to 4}</code></li>
            <li>Affichage en liste : <code>.cell-flex.cell-row</code></li>
        </ul>
    </p>';
$lang['sandbox.layout.grid.free']        = 'Affichage libre';
$lang['sandbox.layout.grid.free.clue']   = 'Chaque élément enfant est tributaire de sa propre largeur (au maximum de 100% de la largeur de l \'élément parent). Les éléments enfants s\'alignent horizontalement jusqu\'au maximum de la largeur du parent, puis passent à la ligne automatiquement.';
$lang['sandbox.layout.grid.mosaic']      = 'Affichage en mosaïc';
$lang['sandbox.layout.grid.mosaic.clue'] = 'Les éléments enfants sans dimension ont leur largeur définie par la classe du parent.';
$lang['sandbox.layout.grid.list']        = 'Affichage en liste';
$lang['sandbox.layout.grid.list.clue']   = 'Quelles que soient leur largeur, les éléments enfants sont affichés les uns en dessous des autres. S\'ils n\'ont pas de largeur définie, il prendront 100% de la largeur du parent.';
$lang['sandbox.layout.grid.forced']      = 'Affichage forcé';
$lang['sandbox.layout.grid.forced.clue'] = 'Quel que soit le type d\'affichage de l\'élément parent, on peut forcer la largeur de chaque enfant et des classes prédéfinies sont disponibles. <span class="pinned small">(Pour les écrans > 1024px)</span>';

// Cell
$lang['sandbox.layout.cell']          = 'Cellules <code>.cell</code>';
$lang['sandbox.layout.cell.clue']     = 'La classe <code>.cell</code> permet une mise en page spécifique pré-définie par des sous-classes.';
$lang['sandbox.layout.cell.columns']  = 'Liste d\'éléments en mosaïque';
$lang['sandbox.layout.cell.row']      = 'Liste d\'éléments en liste';
$lang['sandbox.layout.cell.title']    = 'Titre de l\'élément';
$lang['sandbox.layout.cell.all']      = 'Eléments de cellule';
$lang['sandbox.layout.title.more']    = 'Informations du contenu';
$lang['sandbox.layout.title.alert']   = 'Message d\'alerte';
$lang['sandbox.layout.title.content'] = 'Contenu';
$lang['sandbox.layout.title.form']    = 'Formulaire';
$lang['sandbox.layout.title.list']    = 'Liste';
$lang['sandbox.layout.table']         = 'Tableau';
$lang['sandbox.layout.header']        = 'Entête';
$lang['sandbox.layout.title.footer']  = 'Pied de cellule';

// Messages
$lang['sandbox.layout.messages.and.coms'] = 'Messages et commentaires';
$lang['sandbox.layout.messages.login']    = 'admin';
$lang['sandbox.layout.messages.level']    = 'Administrateur';
$lang['sandbox.layout.user.sign']         = 'Signature du membre';

// Sortables
$lang['sandbox.layout.sortables']             = 'Eléments réorganisables';
$lang['sandbox.layout.sortables.legend']      = 'Liste des éléments';
$lang['sandbox.layout.sortables.description'] = '!! Les déplacements sont désactivés !!';

$lang['sandbox.layout.sortable.dnd']    = 'Drag & Drop';
$lang['sandbox.layout.static.sortable'] = 'Elément positionné';
$lang['sandbox.layout.moving.sortable'] = 'Elément en mouvement';
$lang['sandbox.layout.dropzone']        = 'déplacer ici';
$lang['sandbox.layout.sortable.move']   = 'Déplacer';

?>
