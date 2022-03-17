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

// fa
$lang['sandbox.icons.fa']        = ' Font-Awesome';
$lang['sandbox.icons.fa.sample'] = 'Liste des icônes disponibles';
$lang['sandbox.icons.fa.search'] = 'Recherche d\'icônes';

$lang['sandbox.icons.fa.howto']                       = 'Comment ça marche ?';
$lang['sandbox.icons.fa.howto.clue']                  = 'Font-Awesome est une <span class="text-strong">icon font</span>: une police de caractère qui permet d\'afficher des icônes simplement.';
$lang['sandbox.icons.fa.howto.update']                = 'Elle est implémentée depuis la version 4.1 de PHPBoost. Chaque mise à jour de Font-Awesome est implémentée dans la mise à jour suivante de PHPBoost.';
$lang['sandbox.icons.fa.howto.html']                  = 'En HTML';
$lang['sandbox.icons.fa.howto.html.class']            = 'On utilise le nom de l\'icône en tant que classe : ';
$lang['sandbox.icons.fa.howto.html.class.result.i']   = 'donnera l\'icône "edit" suivie du texte : ';
$lang['sandbox.icons.fa.howto.html.class.result.a']   = 'donnera le lien précédé de l\'icône "globe" : ';
$lang['sandbox.icons.fa.howto.html.class.result.all'] = 'Il en est de même pour tout type de balise HTML.';
$lang['sandbox.icons.fa.howto.css']                   = 'En CSS';
$lang['sandbox.icons.fa.howto.css.class']             = 'Il faut définir votre classe, puis le code de votre icône en tant que contenu du ::before ou du ::after de la classe :';
$lang['sandbox.icons.fa.howto.css.css.code']          = 'Code CSS :';
$lang['sandbox.icons.fa.howto.css.html.code']         = 'Code HTML :';
$lang['sandbox.icons.fa.howto.bbcode']                = 'En BBCode';
$lang['sandbox.icons.fa.howto.bbcode.some.icons']     = 'Les icônes les plus utilisées dans PHPBoost sont déjà implémentées dans le menu bbcode. Vous pouvez les sélectionner en cliquant sur l\'icône du menu:';
$lang['sandbox.icons.fa.howto.bbcode.tag']            = 'Si l\'icône que vous désirez utiliser n\'apparait pas dans la liste, vous pouvez utiliser la balise [fa] comme suit:';
$lang['sandbox.icons.fa.howto.bbcode.icon.name']      = '<pre class="precode precode-inline"><code>[fa]nom de l\'icône[/fa]</code></pre>';
$lang['sandbox.icons.fa.howto.bbcode.icon.test']      = 'Par exemple, <pre class="precode precode-inline"><code>[fa]cubes[/fa]</code></pre> donnera l\'icône:';
$lang['sandbox.icons.fa.howto.bbcode.icon.variants']  = 'Les variantes sont possibles en BBCode et sont expliquées dans ';
$lang['sandbox.icons.fa.howto.variants']              = 'Les variantes';
$lang['sandbox.icons.fa.howto.variants.clue']         = 'Font-Awesome propose une panoplie de variantes telles que la taille de l\'icône, l\'animation, la rotation, l\'empilement et bien d\'autres.';
$lang['sandbox.icons.fa.howto.variants.list']         = 'Leur fonctionnement est expliqué ici (anglais) : ';
$lang['sandbox.icons.fa.howto.variants.spinner']      = 'donnera l\'icône "spinner", animée en rotation et faisant 2 fois sa taille initiale : ';

// Icomoon
$lang['sandbox.icons.icomoon'] = 'IcoMoon';

$lang['sandbox.icons.icomoon.howto.clue'] = '
    <p>L\'application Icomoon permet d\'ajouter des icônes personnalisées sous forme d\'icon font à l\'instar de Font-Awesome.</p>
    <p>Ainsi, moyennant une petite astuce à la création des icônes, il sera possible de les utiliser autant en html que dans le contenu des articles via le bbcode.</p>
';
$lang['sandbox.icons.icomoon.howto.update'] = 'Cette méthode est implémentable quelque soit la version de PHPBoost et une occurence appelée <span class="text-strong">ICOBoost</span> est implémentée depuis la version 6.0. Cette dernière est nécessaire pour afficher certaines icônes dans phpboost qui n\'existent pas dans Font-Awesome.';
$lang['sandbox.icons.icomoon.howto.guide.title'] = 'L\'application';
$lang['sandbox.icons.icomoon.howto.guide'] = '
    <p>
        Sur <a href="https://icomoon.io/" class="pinned bgc moderator offload" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> icomoon.io</a>, cliquer sur <span class="pinned bgc-full moderator"><i class="fa fa-puzzle-piece"></i> IcoMoon App</span>.
        <ul>
            <li>
                Avec le menu hamburger, choisir autant de <pre class="precode precode-inline"><code>New Empty Set</code></pre> que l\'on souhaite (1 par catégorie d\'icônes par exemple).
            </li>
            <li>
                Importer les fichiers svg depuis un ordinateur avec le menu hamburger de chaque IconSet, ou avec "Add Icons From Library" pour choisir une librairie disponible sur le site.
            </li>
            <li>
                Sélectionner toutes les icônes voulues (icône selectionée = entourée en orange).
            </li>
            <li>
                Générer le pack d\'icônes avec le bouton <span class="pinned bgc administrator">Generate Font F</span> en bas à droite.
            </li>
            <li>
                Avant de télécharger le pack, il faut modifier les <span class="pinned bgc administrator"><i class="fa fa-cog"></i>Preferences</span> et, <span class="warning"> à minima</span>,
                remplir le champ <pre class="precode precode-inline"><code>Class Prefix</code></pre> avec <pre class="precode precode-inline"><code>fa-</code></pre> pour que le bbcode de PHPBoost soit pris en compte
                et que le pack bénéficie de toutes les fonctionnalités et variantes de Font-Awesome. <br />
                Il est bien sûr conseillé de remplir les autres champs afin de personnaliser au mieux votre pack et ainsi, éviter les conflits entre différentes bibliothèques.
            </li>
        </ul>
    </p>
';
$lang['sandbox.icons.icomoon.howto.integrate.title'] = 'L\'intégration du pack dans PHPBoost';
$lang['sandbox.icons.icomoon.howto.integrate'] = '
    <p>
        Il est possible d\'intégrer autant de pack que l\'on veut.
        <ul>
            <li>
                Créer un dossier au nom du pack dans le thème utilisé.
            </li>
            <li>
                Copier le fichier <pre class="precode precode-inline"><code>style.css</code></pre> et le dossier <pre class="precode precode-inline"><code>fonts</code></pre> du pack téléchargé depuis icomoon.io
            </li>
            <li>
                Déclarer le fichier <pre class="precode precode-inline"><code>style.css</code></pre> dans le <pre class="precode precode-inline"><code>frame.tpl</code></pre> ou le <pre class="precode precode-inline"><code>@import.css</code></pre> du thème.
            </li>
        </ul>
    </p>
';
$lang['sandbox.icons.icomoon.howto.sample.title'] = 'Exemples avec ICOBoost';
$lang['sandbox.icons.icomoon.howto.sample'] = '
    <p>
        Dans les préférences du pack sur icomoon, il a été choisi :
        <ul>
            <li>
                <pre class="precode precode-inline"><code>fa-iboost-</code></pre> dans le champ <pre class="precode precode-inline"><code>Class Prefix</code></pre>
            </li>
            <li>
                <pre class="precode precode-inline"><code>.iboost</code></pre> dans <pre class="precode precode-inline"><code>CSS Selector /</code></pre><pre class="precode precode-inline"><i class="far fa-dot-circle"></i><code> Use a class</code></pre>
            </li>
        </ul>
    </p>
    <h6>En HTML</h6>
    <p>
        <pre class="precode precode-inline"><code>&lt;i class="fa iboost fa-iboost-email">&lt;/i></code></pre> <i class="fa iboost fa-iboost-email"></i>
    </p>
    <h6>En BBCode</h6>
    <p>
        <pre class="precode precode-inline"><code>[fa=iboost]iboost-phpboost[/fa]</code></pre> <i class="fa iboost fa-iboost-phpboost"></i>
    </p>
';
?>
