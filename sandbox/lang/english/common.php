<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 23
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

// Module titles
$lang['sandbox.module.title'] = 'Sandbox';

$lang['sandbox.admin.render']  = 'Renders in administration';
$lang['sandbox.forms']         = 'Forms';
$lang['sandbox.components']    = 'Componants';
$lang['sandbox.layout']        = 'Layout';
$lang['sandbox.bbcode']        = 'BBCode';
$lang['sandbox.menus']         = 'Menus';
$lang['sandbox.menus.nav']     = 'Navigation menus ';
$lang['sandbox.menus.content'] = 'Content menus';
$lang['sandbox.icons']         = 'Icons ';
$lang['sandbox.miscellaneous'] = 'Miscellaneous';
$lang['sandbox.table']         = 'Tables';
$lang['sandbox.email']         = 'Email';
$lang['sandbox.template']      = 'Template';
$lang['sandbox.php']           = 'PHP tests';
$lang['sandbox.lang']          = 'Languages';

$lang['sandbox.phpboost.doc'] = 'PHPBoost documentation';

$lang['sandbox.pinned.php']    = '<span class ="pinned moderator smallest" data-tooltip-pos ="top" aria-label ="This element can be added by php"><i class    ="fa fa-fw fa-terminal" aria-hidden ="true"></i></span>';
$lang['sandbox.pinned.bbcode'] = '<span class ="pinned member smallest" data-tooltip-pos    ="top" aria-label ="This element can be added by bbcode"><i class ="fa fa-fw fa-code" aria-hidden     ="true"></i></span>';

// Home page
$lang['sandbox.welcome.message'] = '
    <p>Welcome to the Sandbox module.</p>
    <p class="align-center">In this module, you can test the differents componants of the PHPBoost\'s framework : <span class="pinned visitor big"><i class="fa iboost fa-iboost-phpboost"></i> FWKBoost</span></p>
    <p>The top menu <i class="fa fa-hard-hat"></i> allows you to quickly switch between different pages.</p>
';

$lang['sandbox.welcome.see']   = 'Front view';
$lang['sandbox.welcome.admin'] = 'Admin view';

$lang['sandbox.welcome.builder']   = 'The render of the different features using the PHP builder: form fields, maps, links menu, etc.';
$lang['sandbox.welcome.component'] = 'The render of componants from the HTML/CSS/JS FWKBoost framework.';
$lang['sandbox.welcome.bbcode']    = 'The render of the sepcific elements from the bbcode, using a different design from FWKBoost.';
$lang['sandbox.welcome.layout']    = 'Render of different layouts, messages, cells, grid, of FWKBoost.';
$lang['sandbox.welcome.menu']      = 'The render of navigation menus depending on each available location, and some of content menus.';
$lang['sandbox.welcome.misc']      = 'Various PHP test pages.';

$lang['sandbox.welcome.see.nav']     = 'Navigation';
$lang['sandbox.welcome.see.content'] = 'Contenu';

// Lorem
$lang['sandbox.lorem.short.content'] = 'Etiam hendrerit, tortor et faucibus dapibus, eros orci porta eros, in facilisis ipsum ipsum at nisl';
$lang['sandbox.lorem.medium.content'] = '
    Fusce vitae consequat nisl. Fusce vestibulum porta ipsum ac consectetur. Duis finibus mauris eu feugiat congue.
    Aenean aliquam accumsan ipsum, ac dapibus dui ultricies non. In hac habitasse platea dictumst. Aenean mi nibh, varius vel lacus at, tincidunt luctus eros.
    In hac habitasse platea dictumst. Vestibulum luctus lorem nisl, et hendrerit lectus dapibus ut. Phasellus sit amet nisl tortor.
    Aenean pulvinar tellus nulla, sit amet mattis nisl semper eu. Phasellus efficitur nisi a laoreet dignissim. Aliquam erat volutpat.
';
$lang['sandbox.lorem.large.content'] = '
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit odio urna, blandit pharetra elit
    scelerisque tempor. Nulla dapibus felis orci, at consectetur orci auctor eget. Donec eros lectus, mollis eget auctor vel, convallis ac mauris.
    Cras imperdiet, erat ac semper volutpat, libero orci varius mi, et ullamcorper quam urna vitae augue. Maecenas maximus vitae diam vel porta.
    Pellentesque dignissim dolor eu neque aliquet viverra. Maecenas tincidunt, mi non gravida tincidunt, lectus elit gravida massa,
    sed viverra tortor diam pretium metus. In hac habitasse platea dictumst. Ut velit turpis, sollicitudin non risus et, pretium efficitur leo.
    Integer elementum faucibus finibus. Nullam et felis sit amet felis blandit iaculis. Vestibulum massa arcu, finibus id enim ac, commodo aliquam metus.
    Vestibulum feugiat urna nunc, et eleifend velit posuere ac. Vestibulum sagittis tempus nunc, sit amet dignissim ipsum sollicitudin eget.
';

// Helpers
$lang['sandbox.source.code'] = 'See the source code';

// Template
$lang['sandbox.string_template.result'] = '
    <p>Template generation time without cache : :non_cached_time secondes.</p>
    <p>Template generation time with cache : :cached_time secondes.</p>
    <p>String length : :string_length caractères.</p>
';

// Configuration
$lang['sandbox.superadmin.enabled'] = 'Limit the access to only one administrator';
$lang['sandbox.superadmin.id'] = 'Choose the administrator';
$lang['sandbox.is.not.admin'] = 'The member is not an administrator or don\'t exists';

// Authorizations
$lang['config.authorizations.read']  = 'Read authorizations';
?>
