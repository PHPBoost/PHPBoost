<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 12
 * @since       PHPBoost 5.2 - 2019 07 30
*/

####################################################
#                    English                       #
####################################################

// Multitabs
$lang['multitabs.accordion.title']  = 'Accordion menu';
$lang['multitabs.modal.title']  = 'Modal window';
$lang['multitabs.tabs.title']  = 'Tabs menu';

$lang['multitabs.definition']  = '
    Multitabs.js is a jQuery plugin witch allows to manage 3 kinds of hidden content behaviour:
    <ul>
        <li>Accordion menus</li>
        <li>Modal windows</li>
        <li>Tabs menus</li>
    </ul>
    For this demo, triggers are a list of links, but triggering are based on data attributes "data-[plugin]",
    so they can be any html tag.
';
$lang['multitabs.js'] = '
    By default, PHPBoost get 4 declarations:
        <ul>
            <li><h6>Accordion</h6></li>
            <li>jQuery(\'.accordion-container.basic [data-accordion]\').multiTabs({ pluginType: \'accordion\'});</li>
            <li>jQuery(\'.accordion-container.siblings [data-accordion]\').multiTabs({ pluginType: \'accordion\', accordionSiblings: true });</li>
            <li><h6>Modal</h6></li>
            <li>jQuery(\'.modal-container [data-modal]\').multiTabs({ pluginType: \'modal\' });</li>
            <li><h6>Tabs</h6></li>
            <li>jQuery(\'.tabs-container [data-tabs]\').multiTabs({ pluginType: \'tabs\' });</li>
        </ul>
        For the "basic" accordion, panels are independent (e.g html case); for the "siblings" one, panels are dependent, only one can be open at a time (e.g php).<br />
        You can define animations using the plugin options and with adding the <a href="https://daneden.github.io/animate.css/">Animate.css</a>framework to your theme.
';

$lang['multitabs.html']  = 'HTML case';
$lang['multitabs.form']  = 'PHP case';
$lang['multitabs.open.modal']  = 'Open the modal window';

$lang['multitabs.panel.title']  = 'Pannel title ';
$lang['multitabs.menu.title']  = 'Panel';
$lang['multitabs.form.subtitle']  = 'Subtitle';
$lang['multitabs.form.input']  = 'Text field';

?>
