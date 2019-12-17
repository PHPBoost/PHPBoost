<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 30
 * @since       PHPBoost 4.0 - 2019 07 30
*/

####################################################
#                    French                        #
####################################################

// EasyTabs
$lang['plugins.tabs.title']  = 'Menu tabulaire';
$lang['plugins.wizard.title']  = 'Menu wizard';
$lang['plugins.tooltip.title']  = 'Tooltip';

$lang['plugins.title.html']  = 'Déclaration en HTML';
$lang['plugins.title.form']  = 'Déclaration dans un formulaire php';

$lang['plugins.menu.title']  = 'Panneau';
$lang['plugins.last.step']  = 'Panneau final';
$lang['plugins.form.title']  = 'Titre du Panneau';
$lang['plugins.form.subtitle']  = 'Sous Titre';
$lang['plugins.form.input']  = 'Champ texte';

$lang['plugins.tooltip']  = 'Tooltip';
$lang['plugins.tooltip.desc']  = 'Par défaut, le tooltip s\'applique à toute balise possendant un attribut [aria-label]';
$lang['plugins.tooltip.eg.basic']  = 'Au survol, la description déclarée dans l\'attribut aria-label apparait.';
$lang['plugins.tooltip.label.basic']  = 'ce plugin supporte les balises html sans attribut dans la description.
    <br /><br />saut de ligne<br /><br />
    paragraphe<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, enim.</p>
    <i>Texte en italique</i>
    <br />etc
';
$lang['plugins.tooltip.eg.options']  = '
    Au survol, la description déclarée dans l\'attribut aria-label est remplacée par un texte alternatif
    et des options ont été ajoutées.
';
$lang['plugins.tooltip.label.options']  = '
    Texte alternatif<br />Position forcée à droite<br />Ajout de classes de personnalisation
';
$lang['plugins.tooltip.options']  = '
    Options: <br />
    <pre class="inline">data-tooltip</pre> pour ajouter un texte alternatif <br />
    <pre class="inline">data-tooltip-pos</pre> pour forcer la position du tooltip ("t","r","b","l") <br />
    <pre class="inline">data-tooltip-class</pre> pour ajouter des class de personnalisation ("display-none" pour ne pas afficher le tooltip mais laisser le aria-label)<br />
';
?>
