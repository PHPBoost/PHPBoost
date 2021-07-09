<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 09
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

// Typogrphie
$lang['sandbox.typography'] = 'Typography';
$lang['component.titles']   = 'Titles';
$lang['component.title']    = 'Title';
$lang['component.items']    = 'Elements';
$lang['component.item']     = 'Element';

$lang['sandbox.sizes']           = 'Text Sizes';
$lang['component.item.smallest'] = 'Smallest text';
$lang['component.item.smaller']  = 'Smaller text';
$lang['component.item.small']    = 'Small text';
$lang['component.item.big']      = 'Big text';
$lang['component.item.bigger']   = 'Bigger text';
$lang['component.item.biggest']  = 'Biggest text';

$lang['component.styles']              = 'Styles';
$lang['component.item.bold']           = 'Bold text';
$lang['component.item.italic']         = 'Italic text';
$lang['component.item.underline']      = 'Underline text';
$lang['component.item.strike']         = 'Strike text';
$lang['component.link']                = 'Hypertext link';
$lang['component.item.ellipsis']       = 'Ellipsis element';
$lang['component.item.left']           = 'Left aligned element';
$lang['component.item.center']         = 'Center aligned element';
$lang['component.item.right']          = 'Right aligned element';
$lang['component.item.float.left']     = 'Floating left element';
$lang['component.item.float.right']    = 'Floating right element';
$lang['component.item.stretch.center'] = 'justify';
$lang['component.item.stretch.right']  = 'on width';
$lang['component.item.pinned']         = 'Pinned element';
$lang['component.item.stack.index']    = 'Element with index';

$lang['component.color']             = 'Colors';
$lang['component.color.text']        = 'Text color';
$lang['component.color.bgc']         = 'Transparent background color';
$lang['component.color.bgc.full']    = 'Plain background color';
$lang['component.color.clueription'] = '
    <p>For the demo, color classes are shown with the <code class="language-css">.pinned</code> class and can match to any html tag .</p>
    <p>Borders are colored only if the element has some.</p>
';

// Media
$lang['sandbox.media']           = 'Media';
$lang['component.image']         = 'Pictures';
$lang['component.caption.image'] = 'Picture with caption';
$lang['component.lightbox']      = 'Lightbox';
$lang['component.youtube']       = 'Youtube';
$lang['component.movie']         = 'Video';
$lang['component.audio']         = 'Audio';

// Lists
$lang['component.lists'] = 'Lists';

$lang['component.progress.bar'] = 'Progress bar';

$lang['component.explorer'] = 'Explorer';
$lang['component.root']     = 'Root';
$lang['component.tree']     = 'Tree';
$lang['component.cat']      = 'Category';
$lang['component.file']     = 'File';

$lang['component.notation']                 = 'Notation';
$lang['component.notation.possible.values'] = 'Possible values';
$lang['component.notation.example']         = 'Example for a rating of 2.4 on 5';

$lang['component.link.icon'] = 'Item with icon';
$lang['component.link.img']  = 'Item with picture';
$lang['component.link']      = 'Item de Liste';
$lang['component.panel']     = 'Panel';
// Modal
$lang['component.modal'] = 'Modal';

//Pagination
$lang['component.pagination']      = 'Pagination';
$lang['component.pagination.prev'] = 'Vers la première page';
$lang['component.pagination.page'] = 'Vers la page';
$lang['component.pagination.this'] = 'Page courrante';
$lang['component.pagination.next'] = 'Vers la dernière page';

//Blockquote
$lang['component.quote']    = 'Quote';
$lang['component.code']     = 'Code';
$lang['component.code.php'] = 'PHP code';
$lang['component.hidden']   = 'Hidden text';

//Tables
$lang['component.table']                      = 'Tables';
$lang['component.table.clueription']          = 'Description';
$lang['component.table.clueription.content']  = 'Table description';
$lang['component.table.name']                 = 'Name';
$lang['component.table.caption']              = 'This is a table';
$lang['component.table.caption.no.header']    = 'This is a table with no header';
$lang['component.table.author']               = 'Author';
$lang['component.table.test']                 = 'Test';
$lang['component.table.header']               = 'Header';
$lang['component.table.sort.up']              = 'Sort up';
$lang['component.table.sort.down']            = 'Sort down';
$lang['component.table.responsive.header']    = 'Responsive table with header';
$lang['component.table.responsive.no.header'] = 'Responsive table without header';

$lang['component.alert.messages']          = 'Alert messages';
$lang['component.message.success']         = 'This is a success message';
$lang['component.message.notice']          = 'This is a notice message';
$lang['component.message.warning']         = 'This is a warning message';
$lang['component.message.error']           = 'This is a error message';
$lang['component.message.question']        = 'This is a question:<br /> is the two-lines display working correctly?';
$lang['component.message.member']          = 'This is a message limited to members';
$lang['component.message.modo']            = 'This is a message limited to moderators';
$lang['component.message.admin']           = 'This is a message limited to administrators';
$lang['component.message.float.unlimited'] = 'This is a floating message without time limit';
$lang['component.message.float.limited']   = 'This is a floating message with time limit';
$lang['component.message.float.display']   = 'Display floating messages';

// Tooltips
$lang['component.tooltip']        = 'Tooltip';
$lang['component.tooltip.custom'] = 'Customized tooltip';
$lang['component.tooltip.clue'] = '
    A tooltip is a description indexed to an element witch is hidden by deault and been revealed on hover the element (The following examples are shown with the <code class="language-css">.pinned</code> class). <br />
    By default in PHPBoost the tooltips are applied to all elements with <code class="language-html">aria-label</code> attribute.
';
$lang['component.tooltip.eg.basic']    = 'On hover, the description setted in the aria-label is revealed.';
$lang['component.tooltip.label.basic'] = '
    This plugin allows html tag without attributes.
    <br /><br />line break<br /><br />
    paragraphe<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, enim.</p>
    <i>Italics</i>
    <br />etc
';
$lang['component.tooltip.options'] = '
    On hover the aria-label description is replaced by aternative text and options have been added.
';
$lang['component.tooltip.eg.options']  = 'On hover, alternative text with options is shown instead of aria-label description';
$lang['component.tooltip.alt.options'] = 'Alternatif text with options';
$lang['component.tooltip.options'] = '
    Options: <br />
    <code class="language-markup">data-tooltip</code> to add alternative text <br />
    <code class="language-markup">data-tooltip-pos</code> to force position of the tooltip (top|right|bottom|left) <br />
    <code class="language-markup">data-tooltip-class</code> to add custom classes ("display-none" to keep aria-label but hide the tooltip)<br />
';
?>
