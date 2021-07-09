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
#                    French                        #
####################################################

// fa
$lang['icons.fa']        = ' Font-Awesome';
$lang['icons.fa.sample'] = 'Quelques exemples';
$lang['icons.fa.social'] = 'Réseaux sociaux';
$lang['icons.fa.screen'] = 'Ecrans';
$lang['icons.fa.icon']   = 'Icône';
$lang['icons.fa.name']   = 'Nom';
$lang['icons.fa.code']   = 'Code';
$lang['icons.fa.list']   = 'La liste complète des icônes et de leur code associé : ';

$lang['icons.fa.howto']                       = 'Comment ça marche ?';
$lang['icons.fa.howto.clue']               = 'Font-Awesome est une <span class="text-strong">icon font</span>: une police de caractère qui permet d\'afficher des icônes simplement.';
$lang['icons.fa.howto.update']                = 'Elle est implémentée depuis la version 4.1 de PHPBoost. Chaque mise à jour de Font-Awesome est implémentée dans la mise à jour suivante de PHPBoost.';
$lang['icons.fa.howto.html']                  = 'En HTML';
$lang['icons.fa.howto.html.class']            = 'On utilise le nom de l\'icône en tant que classe : ';
$lang['icons.fa.howto.html.class.result.i']   = 'donnera l\'icône "edit" suivie du texte : ';
$lang['icons.fa.howto.html.class.result.a']   = 'donnera le lien précédé de l\'icône "globe" : ';
$lang['icons.fa.howto.html.class.result.all'] = 'Il en est de même pour tout type de balise HTML.';
$lang['icons.fa.howto.css']                   = 'En CSS';
$lang['icons.fa.howto.css.class']             = 'Il faut définir votre classe, puis le code de votre icône en tant que contenu du ::before ou du ::after de la classe :';
$lang['icons.fa.howto.css.css.code']          = 'Code CSS :';
$lang['icons.fa.howto.css.html.code']         = 'Code HTML :';
$lang['icons.fa.howto.bbcode']                = 'En BBCode';
$lang['icons.fa.howto.bbcode.some.icons']     = 'Les icônes les plus utilisées dans PHPBoost sont déjà implémentées dans le menu bbcode. Vous pouvez les sélectionner en cliquant sur l\'icône du menu:';
$lang['icons.fa.howto.bbcode.tag']            = 'Si l\'icône que vous désirez utiliser n\'apparait pas dans la liste, vous pouvez utiliser la balise [fa] comme suit:';
$lang['icons.fa.howto.bbcode.icon.name']      = '[fa]nom de l\'icône[/fa]';
$lang['icons.fa.howto.bbcode.icon.test']      = 'Par exemple, <code class="language-markup">[fa]cubes[/fa]</code> donnera l\'icône:';
$lang['icons.fa.howto.bbcode.icon.variants']  = 'Les variantes sont possibles en BBCode et sont expliquées dans ';
$lang['icons.fa.howto.variants']              = 'Les variantes';
$lang['icons.fa.howto.variants.clue']      = 'Font-Awesome propose une panoplie de variantes telles que la taille de l\'icône, l\'animation, la rotation, l\'empilement et bien d\'autres.';
$lang['icons.fa.howto.variants.list']         = 'Leur fonctionnement est expliqué ici (anglais) : ';
$lang['icons.fa.howto.variants.spinner']      = 'donnera l\'icône "spinner", animée en rotation et faisant 2 fois sa taille initiale : ';

// Icomoon
$lang['icons.icomoon'] = 'IcoMoon';

$lang['icons.icomoon.howto.clue'] = '
    <p>L\'application Icomoon permet d\'ajouter des icônes personnalisées sous forme d\'icon font à l\'instar de Font-Awesome.</p>
    <p>Ainsi, moyennant une petite astuce à la création des icônes, il sera possible de les utiliser autant en html que dans le contenu des articles via le bbcode.</p>
';
$lang['icons.icomoon.howto.update'] = 'Cette méthode est implémentable quelque soit la version de PHPBoost et une occurence appelée <span class="text-strong">ICOBoost</span> est implémentée depuis la version 6.0. Cette dernière est nécessaire pour afficher certaines icônes dans phpboost qui n\'existent pas dans Font-Awesome.';
$lang['icons.icomoon.howto.guide.title'] = 'L\'application';
$lang['icons.icomoon.howto.guide'] = '
    <p>
        Sur <a href="https://icomoon.io/" class="pinned bgc moderator offload" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> icomoon.io</a>, cliquer sur <span class="pinned bgc-full moderator"><i class="fa fa-puzzle-piece"></i> IcoMoon App</span>.
        <ul>
            <li>
                Avec le menu hamburger, choisir autant de <code class="language-markup">New Empty Set</code> que l\'on souhaite (1 par catégorie d\'icônes par exemple).
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
                remplir le champ <code class="language-markup">Class Prefix</code> avec <code class="language-css">fa-</code> pour que le bbcode de PHPBoost soit pris en compte
                et que le pack bénéficie de toutes les fonctionnalités et variantes de Font-Awesome. <br />
                Il est bien sûr conseillé de remplir les autres champs afin de personnaliser au mieux votre pack et ainsi, éviter les conflits entre différentes bibliothèques.
            </li>
        </ul>
    </p>
';
$lang['icons.icomoon.howto.integrate.title'] = 'L\'intégration du pack dans PHPBoost';
$lang['icons.icomoon.howto.integrate'] = '
    <p>
        Il est possible d\'intégrer autant de pack que l\'on veut.
        <ul>
            <li>
                Créer un dossier au nom du pack dans le thème utilisé.
            </li>
            <li>
                Copier le fichier <code class="language-markup">style.css</code> et le dossier <code class="language-markup">fonts</code> du pack téléchargé depuis icomoon.io
            </li>
            <li>
                Déclarer le fichier <code class="language-markup">style.css</code> dans le <code class="language-markup">frame.tpl</code> ou le <code class="language-markup">@import.css</code> du thème.
            </li>
        </ul>
    </p>
';
$lang['icons.icomoon.howto.sample.title'] = 'Exemples avec ICOBoost';
$lang['icons.icomoon.howto.sample'] = '
    <p>
        Dans les préférences du pack sur icomoon, il a été choisi :
        <ul>
            <li>
                <code class="language-markup">fa-iboost-</code> dans le champ <code class="language-markup">Class Prefix</code>
            </li>
            <li>
                <code class="language-markup">.iboost</code> dans <code class="language-markup">CSS Selector/</code><i class="far fa-dot-circle"></i><code class="language-markup"> Use a class</code>
            </li>
        </ul>
    </p>
    <h6>En HTML</h6>
    <p>
        <code class="language-html">&lt;i class="fa iboost fa-iboost-email">&lt;/i></code> <i class="fa iboost fa-iboost-email"></i>
    </p>
    <h6>En BBCode</h6>
    <p>
        <code class="language-markup">[fa=iboost]iboost-phpboost[/fa]</code> <i class="fa iboost fa-iboost-phpboost"></i>
    </p>
';
?>
