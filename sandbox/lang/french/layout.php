<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 31
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
$lang['sandbox.layout.grid']                           = 'Grille d\'affichage';
$lang['sandbox.layout.grid.clue']                      = '<p>Il y a 3 types d\'affichage "en cellule" (<code>.cell-flex</code>) dans PHPBoost: libre (<code>.cell-inline</code>), en mosaïc (<code>.cell-columns-{1 to 4}</code>) et en liste (<code>.cell-row</code>).</p>';
$lang['sandbox.layout.grid.free']                      = 'Cellules libres';
$lang['sandbox.layout.grid.free.clue']                 = 'Chaque cellule est tributaire de ses propres dimensions. Les cellules s\'alignent au maximum de la largeur du conteneur, puis passent à la ligne automatiquement.';
$lang['sandbox.layout.grid.free.forced.clue']          = 'On peut forcer la largeur des cellules qui n\'ont pas de dimension définie. <span class="pinned small">(Pour les écrans > 1024px)</span>';
$lang['sandbox.layout.grid.block.columns']             = 'Cellules en mosaïc';
$lang['sandbox.layout.grid.block.columns.clue']        = 'Les cellules sans dimension ont leur largeur définie par la classe du parent.';
$lang['sandbox.layout.grid.block.columns.forced.clue'] = 'On peut aussi forcer la largeur des cellules malgré la mise en page définie par le parent. <span class="pinned small">(Pour les écrans > 1024px)</span>';
$lang['sandbox.layout.grid.list']                      = 'Cellules en liste';
$lang['sandbox.layout.grid.list.clue']                 = 'Quelques soient leurs dimensions, les cellules sont affichées les unes en dessous des autres.';
$lang['sandbox.layout.grid.forced']                    = 'Cellules forcées';

// Cell
$lang['sandbox.layout.cell']          = 'Cellules';
$lang['sandbox.layout.cell.columns']  = 'Liste d\'éléments en mosaïque (défaut)';
$lang['sandbox.layout.cell.row']      = 'Liste d\'éléments en liste';
$lang['sandbox.layout.cell.all']      = 'Eléments de cellule';
$lang['sandbox.layout.title.more']    = 'Informations du contenu';
$lang['sandbox.layout.title.alert']   = 'Message d\'alerte';
$lang['sandbox.layout.title.content'] = 'Contenu';
$lang['sandbox.layout.title.form']    = 'Formulaire';
$lang['sandbox.layout.title.list']    = 'Liste';
$lang['sandbox.layout.sandbox.table'] = 'Tableau';
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
