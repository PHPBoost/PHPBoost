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

// fa
$lang['icons.fa']        = ' Font-Awesome';
$lang['icons.fa.sample'] = 'Some samples';
$lang['icons.fa.social'] = 'Social networks';
$lang['icons.fa.screen'] = 'Screens';
$lang['icons.fa.icon']   = 'Icon';
$lang['icons.fa.name']   = 'Name';
$lang['icons.fa.code']   = 'Code';
$lang['icons.fa.list']   = 'Full list of icons and associated code: ';

$lang['icons.fa.howto']                       = 'How it works?';
$lang['icons.fa.howto.clue']               = 'Font-Awesome is a <span class="text-strong">font-icon</span>: a font built with icons.';
$lang['icons.fa.howto.update']                = 'It is part of PHPBoost since the 4.1 version. Each Font-Awesome update is added to the next version of PHPBoost.';
$lang['icons.fa.howto.html']                  = 'With HTML';
$lang['icons.fa.howto.html.class']            = 'Use the icon name as class: ';
$lang['icons.fa.howto.html.class.result.i']   = 'give the "edit" icon followed by text: ';
$lang['icons.fa.howto.html.class.result.a']   = 'give a link preceded by the "globe" icon: ';
$lang['icons.fa.howto.html.class.result.all'] = 'Use it with any HTML tag.';
$lang['icons.fa.howto.css']                   = 'With CSS';
$lang['icons.fa.howto.css.class']             = 'Define a class, then add the icon code in "content" propertiy in the ::before or ::after of the class:';
$lang['icons.fa.howto.css.css.code']          = 'CSS code:';
$lang['icons.fa.howto.css.html.code']         = 'HTML code:';
$lang['icons.fa.howto.bbcode']                = 'With BBCode';
$lang['icons.fa.howto.bbcode.some.icons']     = 'A list of some icons is shown in the menu. Select one by a click:';
$lang['icons.fa.howto.bbcode.tag']            = 'Make yours with the [fa] BBCode tag as following:';
$lang['icons.fa.howto.bbcode.icon.name']      = '[fa]icon name[/fa]';
$lang['icons.fa.howto.bbcode.icon.test']      = 'E.g., <code class="language-markup">[fa]cubes[/fa]</code> give the icon:';
$lang['icons.fa.howto.bbcode.icon.variants']  = 'Variants font-awesome features are usable with BBCode as explained at ';
$lang['icons.fa.howto.variants']              = 'Variants';
$lang['icons.fa.howto.variants.clue']      = 'Font-Awesome aloows a list of variants feature such as width, animation, rotation, stacking and more.';
$lang['icons.fa.howto.variants.list']         = 'Variants documentation: ';
$lang['icons.fa.howto.variants.spinner']      = 'give the "spinner" icon, animated with rotation and twice initial width: ';

// Icomoon
$lang['icons.icomoon'] = 'IcoMoon';

$lang['icons.icomoon.howto.clue'] = '
    <p>Icomoon app allows to create font-icon with your own icons like Font-Awesome or some else.</p>
    <p>So with a tricky feature while creating your own font-icon, you will be able to use it with the BBCode editor in PHPBoost.</p>
';
$lang['icons.icomoon.howto.update'] = 'It works with any PHPBoost version from the 4.1 and one is already in it since the 6.0 version: <span class="text-strong">ICOBoost</span>. Take care to use it in templates to see some missing icons from Font-Awesome, used in PHPBoost.';
$lang['icons.icomoon.howto.guide.title'] = 'The app';
$lang['icons.icomoon.howto.guide'] = '
    <p>
        At <a href="https://icomoon.io/" class="pinned bgc moderator offload" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> icomoon.io</a>, click on <span class="pinned bgc-full moderator"><i class="fa fa-puzzle-piece"></i> IcoMoon App</span> button.
        <ul>
            <li>
                On hamburger menu, add as <code class="language-markup">New Empty Set</code> as needed (e.g., 1 by category).
            </li>
            <li>
                Import svg files from computer with each IconSet hamburger menu, or "Add Icons From Library" to choose an available one from the website.
            </li>
            <li>
                Select all wanted icons (Orange circled).
            </li>
            <li>
                Generate icons pack with the <span class="pinned bgc administrator">Generate Font F</span> button at the bottom right.
            </li>
            <li>
                Before downloading the pack, you must modify the <span class="pinned bgc administrator"><i class="fa fa-cog"></i>Preferences</span> and, <span class="warning"> at least</span>,
                fill the <code class="language-markup">Class Prefix</code> field with <code class="language-css">fa-</code> to enable it in PHPBoost BBCode
                with all Font-Awesome features. <br />
                Customize all other field to avoid conflicts from other libraries.
            </li>
        </ul>
    </p>
';
$lang['icons.icomoon.howto.integrate.title'] = 'Integration into PHPBoost';
$lang['icons.icomoon.howto.integrate'] = '
    <p>
        You can integrate as many pack as you want.
        <ul>
            <li>
                Create a folder in the template.
            </li>
            <li>
                Copy <code class="language-markup">style.css</code> file and the <code class="language-markup">fonts</code> folder of the downloaded pack from icomoon.io
            </li>
            <li>
                Add the <code class="language-markup">style.css</code> url in <code class="language-markup">frame.tpl</code> or <code class="language-markup">@import.css</code> of the template.
            </li>
        </ul>
    </p>
';
$lang['icons.icomoon.howto.sample.title'] = 'Samples with ICOBoost';
$lang['icons.icomoon.howto.sample'] = '
    <p>
        Have been chosen in the preferences:
        <ul>
            <li>
                <code class="language-markup">fa-iboost-</code> in the <code class="language-markup">Class Prefix</code> field.
            </li>
            <li>
                <code class="language-markup">.iboost</code> in the <code class="language-markup">CSS Selector/</code><i class="far fa-dot-circle"></i><code class="language-markup"> Use a class</code> field
            </li>
        </ul>
    </p>
    <h6>With HTML</h6>
    <p>
        <code class="language-html">&lt;i class="fa iboost fa-iboost-email">&lt;/i></code> <i class="fa iboost fa-iboost-email"></i>
    </p>
    <h6>With BBCode</h6>
    <p>
        <code class="language-markup">[fa=iboost]iboost-phpboost[/fa]</code> <i class="fa iboost fa-iboost-phpboost"></i>
    </p>
';
?>
