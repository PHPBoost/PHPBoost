<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

// Titles
$lang['sandbox.module.title'] = 'Bac à sable';

$lang['sandbox.admin.render']  = 'Rendus dans l\'admin';
$lang['sandbox.forms']         = 'Formulaires';
$lang['sandbox.components']    = 'Composants';
$lang['sandbox.layout']        = 'Mise en page';
$lang['sandbox.bbcode']        = 'BBCode';
$lang['sandbox.menus']         = 'Menus';
$lang['sandbox.menus.nav']     = 'Menus de navigation';
$lang['sandbox.menus.content'] = 'Menus de contenu';
$lang['sandbox.icons']         = 'Icônes';
$lang['sandbox.miscellaneous'] = 'Divers';
$lang['sandbox.table']         = 'Tableaux';
$lang['sandbox.email']         = 'Email';
$lang['sandbox.template']      = 'Template';
$lang['sandbox.php']           = 'Tests PHP';
$lang['sandbox.lang']          = 'Langues';

$lang['sandbox.phpboost.doc'] = 'la documentation de PHPBoost';

$lang['sandbox.pinned.php']    = '<span class ="pinned moderator smallest" data-tooltip-pos ="top" aria-label ="Cet élément peut être ajouté en php"><i class    ="fa fa-fw fa-terminal" aria-hidden ="true"></i></span>';
$lang['sandbox.pinned.bbcode'] = '<span class ="pinned member smallest" data-tooltip-pos    ="top" aria-label ="Cet élément peut être ajouté en bbcode"><i class ="fa fa-fw fa-code" aria-hidden     ="true"></i></span>';

// Home page
$lang['sandbox.welcome.message'] = '
    <p>Bienvenue dans le module Bac à sable.</p>
    <p class="align-center">Vous pouvez tester dans ce module les différents composants du framework de PHPBoost : <span class="pinned visitor big"><i class="fa iboost fa-iboost-phpboost"></i> FWKBoost</span></p>
    <p>Le menu <i class="fa fa-hard-hat"></i> ci-dessus vous permet de naviguer rapidement entre et dans les différentes pages.</p>
';

$lang['sandbox.welcome.see']   = 'Voir';
$lang['sandbox.welcome.admin'] = 'En admin';

$lang['sandbox.welcome.builder']   = 'Le rendu des différents champs de formulaire, textarea, checkbox, etc.';
$lang['sandbox.welcome.component'] = 'Le rendu des différents éléments du framework HTML/CSS/JS FWKBoost de PHPBoost.';
$lang['sandbox.welcome.bbcode']    = 'Le rendu des éléments spécifiques déclarés en bbcode qui apportent un design différent du FWKBoost.';
$lang['sandbox.welcome.layout']    = 'Le rendu des différentes mises en page, messages, cellules, grille d\'affichage, du FWKBoost.';
$lang['sandbox.welcome.menu']      = 'Le rendu des menus de navigations selon les emplacements potentiels, et des menus de contenu.';
$lang['sandbox.welcome.misc']      = 'Diverses pages de test en php.';

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
$lang['sandbox.source.code'] = 'Voir le code source';

// Template
$lang['sandbox.string_template.result'] = '
    <p>Temps de génération du template sans cache : :non_cached_time secondes.</p>
    <p>Temps de génération du template avec cache : :cached_time secondes.</p>
    <p>Longueur de la chaîne : :string_length caractères.</p>
';

// Configuration
$lang['sandbox.superadmin.enabled'] = 'Limiter l\'accès au menu à un seul administrateur';
$lang['sandbox.superadmin.id'] = 'Choix de l\'administrateur';
$lang['sandbox.is.not.admin'] = 'Le membre choisi n\'est pas un administrateur ou n\'existe pas';

// Authorizations
$lang['config.authorizations.read']  = 'Autorisation d\'afficher le mini module';
?>
