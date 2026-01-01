<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 05
 * @since       PHPBoost 5.2 - 2020 05 22
*/

####################################################
#                    English                       #
####################################################

// Generics
$lang['sandbox.layout.titles']    = 'Titles';
$lang['sandbox.layout.title']     = 'Title';
$lang['sandbox.layout.title.sub'] = 'Sub title';
$lang['sandbox.layout.items']     = 'Elements';
$lang['sandbox.layout.item']      = 'Element';

// Grid
$lang['sandbox.layout.grid']      = 'Layout grid';
$lang['sandbox.layout.grid.clue'] = '
    <p>There are three kinds of cells grid, defined on the parent element
        <ul>
            <li>Free layout: <code>.cell-flex.cell-inline</code></li>
            <li>Mosaic layout: <code>.cell-flex.cell-columns-{1 to 4}</code></li>
            <li>List layout: <code>.cell-flex.cell-row</code></li>
        <ul>
    </p>';
$lang['sandbox.layout.grid.free']        = 'Free cells';
$lang['sandbox.layout.grid.free.clue']   = 'Each child is dependent on its own dimensions (at most 100% of the width of the parent element). Cells are horizontally aligned and automaticaly go to the next line when they exeed limits.';
$lang['sandbox.layout.grid.mosaic']      = 'Mosaic grid';
$lang['sandbox.layout.grid.mosaic.clue'] = 'Width of children are defined by the parent container.';
$lang['sandbox.layout.grid.list']        = 'List';
$lang['sandbox.layout.grid.list.clue']   = 'Whatever their width, the child elements are displayed one below the other. If they don\'t have width, they will take 100% of the width of the parent.';
$lang['sandbox.layout.grid.forced']      = 'Forced cells';
$lang['sandbox.layout.grid.forced.clue'] = 'Whatever the setting of the parent, you can force children to be sized and some classes are predefined. <span class="pinned small">(for > 1024px screens)</span>';

// Cell
$lang['sandbox.layout.cell']          = 'Cells <code>.cell</code>';
$lang['sandbox.layout.cell.columns']  = 'Mosaïc items layout (default)';
$lang['sandbox.layout.cell.row']      = 'List items layout';
$lang['sandbox.layout.cell.all']      = 'Cell elements';
$lang['sandbox.layout.cell.title']    = 'Element title';
$lang['sandbox.layout.title.more']    = 'Content information';
$lang['sandbox.layout.title.alert']   = 'Alerts';
$lang['sandbox.layout.title.content'] = 'Content';
$lang['sandbox.layout.title.form']    = 'Form';
$lang['sandbox.layout.title.list']    = 'List';
$lang['sandbox.layout.table']         = 'Table';
$lang['sandbox.layout.header']        = 'Header';
$lang['sandbox.layout.title.footer']  = 'Cell footer';

// Messages
$lang['sandbox.layout.messages.and.coms'] = 'Messages and comments';
$lang['sandbox.layout.messages.login']    = 'admin';
$lang['sandbox.layout.messages.level']    = 'Administrator';
$lang['sandbox.layout.user.sign']         = 'Membre signature';

// Sortables
$lang['sandbox.layout.sortables']             = 'Sortable elements';
$lang['sandbox.layout.sortables.legend']      = 'Elements list';
$lang['sandbox.layout.sortables.description'] = '!! Movings are disabled !!';

$lang['sandbox.layout.sortable.dnd']    = 'Drag & Drop';
$lang['sandbox.layout.static.sortable'] = 'Psotioned element';
$lang['sandbox.layout.moving.sortable'] = 'Sortable en mouvement';
$lang['sandbox.layout.dropzone']        = 'drop here';
$lang['sandbox.layout.sortable.move']   = 'Move';

?>
