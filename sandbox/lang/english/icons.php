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
#                    English                       #
####################################################

// fa
$lang['sandbox.icons.fa']        = ' Font-Awesome';
$lang['sandbox.icons.fa.sample'] = 'Available icons list';
$lang['sandbox.icons.fa.search'] = 'Icon search';

$lang['sandbox.icons.fa.howto']                       = 'How it works?';
$lang['sandbox.icons.fa.howto.clue']                  = 'Font-Awesome is a <span class="text-strong">font-icon</span>: a font built with icons.';
$lang['sandbox.icons.fa.howto.update']                = 'It is part of PHPBoost since the 4.1 version. Each Font-Awesome update is added to the next version of PHPBoost.';
$lang['sandbox.icons.fa.howto.html']                  = 'With HTML';
$lang['sandbox.icons.fa.howto.html.class']            = 'Use the icon name as class: ';
$lang['sandbox.icons.fa.howto.html.class.result.i']   = 'give the "edit" icon followed by text: ';
$lang['sandbox.icons.fa.howto.html.class.result.a']   = 'give a link preceded by the "globe" icon: ';
$lang['sandbox.icons.fa.howto.html.class.result.all'] = 'Use it with any HTML tag.';
$lang['sandbox.icons.fa.howto.css']                   = 'With CSS';
$lang['sandbox.icons.fa.howto.css.class']             = 'Define a class, then add the icon code in "content" propertiy in the ::before or ::after of the class:';
$lang['sandbox.icons.fa.howto.css.css.code']          = 'CSS code:';
$lang['sandbox.icons.fa.howto.css.html.code']         = 'HTML code:';
$lang['sandbox.icons.fa.howto.bbcode']                = 'With BBCode';
$lang['sandbox.icons.fa.howto.bbcode.some.icons']     = 'A list of some icons is shown in the menu. Select one by a click:';
$lang['sandbox.icons.fa.howto.bbcode.tag']            = 'Make yours with the [fa] BBCode tag as following:';
$lang['sandbox.icons.fa.howto.bbcode.icon.name']      = '[fa]icon name[/fa]';
$lang['sandbox.icons.fa.howto.bbcode.icon.test']      = 'E.g., <pre class="precode precode-inline"><code>[fa]cubes[/fa]</code></pre> give the icon:';
$lang['sandbox.icons.fa.howto.bbcode.icon.variants']  = 'Variants font-awesome features are usable with BBCode as explained at ';
$lang['sandbox.icons.fa.howto.variants']              = 'Variants';
$lang['sandbox.icons.fa.howto.variants.clue']         = 'Font-Awesome aloows a list of variants feature such as width, animation, rotation, stacking and more.';
$lang['sandbox.icons.fa.howto.variants.list']         = 'Variants documentation: ';
$lang['sandbox.icons.fa.howto.variants.spinner']      = 'give the "spinner" icon, animated with rotation and twice initial width: ';

// Icomoon
$lang['sandbox.icons.icomoon'] = 'IcoMoon';

$lang['sandbox.icons.icomoon.howto.clue'] = '
    <p>Icomoon app allows to create font-icon with your own icons like Font-Awesome or some else.</p>
    <p>So with a tricky feature while creating your own font-icon, you will be able to use it with the BBCode editor in PHPBoost.</p>
';
$lang['sandbox.icons.icomoon.howto.update'] = 'It works with any PHPBoost version from the 4.1 and one is already in it since the 6.0 version: <span class="text-strong">ICOBoost</span>. Take care to use it in templates to see some missing icons from Font-Awesome, used in PHPBoost.';
$lang['sandbox.icons.icomoon.howto.guide.title'] = 'The app';
$lang['sandbox.icons.icomoon.howto.guide'] = '
    <p>
        At <a href="https://icomoon.io/" class="pinned bgc moderator offload" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> icomoon.io</a>, click on <span class="pinned bgc-full moderator"><i class="fa fa-puzzle-piece"></i> IcoMoon App</span> button.
        <ul>
            <li>
                On hamburger menu, add as <pre class="precode precode-inline"><code>New Empty Set</code></pre> as needed (e.g., 1 by category).
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
                fill the <pre class="precode precode-inline"><code>Class Prefix</code></pre> field with <pre class="precode precode-inline"><code class="language-css">fa-</code></pre> to enable it in PHPBoost BBCode
                with all Font-Awesome features. <br />
                Customize all other field to avoid conflicts from other libraries.
            </li>
        </ul>
    </p>
';
$lang['sandbox.icons.icomoon.howto.integrate.title'] = 'Integration into PHPBoost';
$lang['sandbox.icons.icomoon.howto.integrate'] = '
    <p>
        You can integrate as many pack as you want.
        <ul>
            <li>
                Create a folder in the template.
            </li>
            <li>
                Copy <pre class="precode precode-inline"><code>style.css</code></pre> file and the <pre class="precode precode-inline"><code>fonts</code></pre> folder of the downloaded pack from icomoon.io
            </li>
            <li>
                Add the <pre class="precode precode-inline"><code>style.css</code></pre> url in <pre class="precode precode-inline"><code>frame.tpl</code></pre> or <pre class="precode precode-inline"><code>@import.css</code></pre> of the template.
            </li>
        </ul>
    </p>
';
$lang['sandbox.icons.icomoon.howto.sample.title'] = 'Samples with ICOBoost';
$lang['sandbox.icons.icomoon.howto.sample'] = '
    <p>
        Have been chosen in the preferences:
        <ul>
            <li>
                <pre class="precode precode-inline"><code>fa-iboost-</code></pre> in the <pre class="precode precode-inline"><code>Class Prefix</code></pre> field.
            </li>
            <li>
                <pre class="precode precode-inline"><code>.iboost</code></pre> in the <pre class="precode precode-inline"><code>CSS Selector /</code></pre><pre class="precode precode-inline"><i class="far fa-dot-circle"></i><code> Use a class</code></pre> field
            </li>
        </ul>
    </p>
    <h6>With HTML</h6>
    <p>
        <pre class="precode precode-inline"><code class="language-html">&lt;i class="fa iboost fa-iboost-email">&lt;/i></code></pre> <i class="fa iboost fa-iboost-email"></i>
    </p>
    <h6>With BBCode</h6>
    <p>
        <pre class="precode precode-inline"><code>[fa=iboost]iboost-phpboost[/fa]</code></pre> <i class="fa iboost fa-iboost-phpboost"></i>
    </p>
';
?>
