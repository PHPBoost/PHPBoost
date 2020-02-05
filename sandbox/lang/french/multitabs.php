<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 11
 * @since       PHPBoost 5.2 - 2019 07 30
*/

####################################################
#                    French                        #
####################################################

// Multitabs
$lang['multitabs.accordion.title']  = 'Menu Accordéon';
$lang['multitabs.accordion.title.basic']  = 'Menu Accordéon indépendant';
$lang['multitabs.accordion.title.siblings']  = 'Menu Accordéon conditionné';
$lang['multitabs.modal.title']  = 'Fenêtre modale';
$lang['multitabs.tabs.title']  = 'Menu tabulaire';

$lang['multitabs.definition']  = '
    Multitabs.js est un plugin jQuery qui permet de gérer 3 types de comportement de révélation de contenus cachés:
    <ul>
        <li>Les menus en accordéon</li>
        <li>Les fenêtres en "modal"</li>
        <li>Les menus tabulaires</li>
    </ul>
    Pour les besoins de cette démo, les déclencheurs sont une liste de liens, mais les déclenchements sont basés sur les attributs "data-[plugin]",
    ils peuvent donc être defini sur n\'importe quelle balise html.
';
$lang['multitabs.options.basic'] = 'Basic';
$lang['multitabs.options.siblings'] = 'Siblings';
$lang['multitabs.js'] = '
    Par default, PHPBoost comporte 4 déclarations:
        <ul>
            <li><h6>Accordéon</h6></li>
            <li>jQuery(\'.accordion-container.basic [data-accordion]\').multiTabs({ pluginType: \'accordion\'});</li>
            <li>jQuery(\'.accordion-container.siblings [data-accordion]\').multiTabs({ pluginType: \'accordion\', accordionSiblings: true });</li>
            <li><h6>Modal</h6></li>
            <li>jQuery(\'.modal-container [data-modal]\').multiTabs({ pluginType: \'modal\' });</li>
            <li><h6>Tabulaire</h6></li>
            <li>jQuery(\'.tabs-container [data-tabs]\').multiTabs({ pluginType: \'tabs\' });</li>
        </ul>
        Pour l\'accordéon "basic", les panneaux sont indépendants; pour le "siblings", les panneaux sont dépendants, un seul peut ête ouvert à la fois.<br />
        Vous pouvez définir des animations avec les options du plugin et en ajoutant <a href="https://daneden.github.io/animate.css/">Animate.css</a> à votre thème.
';

$lang['multitabs.html']  = 'Déclaration en HTML';
$lang['multitabs.form']  = 'Déclaration dans un formulaire php';
$lang['multitabs.open.modal']  = 'Ouvrir la fenêtre modale';

$lang['multitabs.panel.title']  = 'Titre du panneau ';
$lang['multitabs.menu.title']  = 'Panneau';
$lang['multitabs.form.subtitle']  = 'Sous Titre';
$lang['multitabs.form.input']  = 'Champ texte';

?>
