<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright            : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', '.');
require_once('./kernel/begin.php');
define('ALTERNATIVE_CSS', 'news');
define('TITLE', 'Votre site à portée de main');
require_once('./kernel/header.php');
require_once('./kernel/framework/content/syndication/feed.class.php');

global $LANG;
?>
       <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left">
                    <span id="news_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                        <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                    </span>&nbsp;
                    <?php echo get_feed_menu('/news/syndication.php'); ?>
                    <h3 class="title valign_middle">Dernières news</h3>
                </div>
                <div style="float:right"></div>
            </div>
            <div class="news_content">
                <?php
                    echo Feed::get_parsed('news', DEFAULT_FEED_NAME, 0, array(), 14);
                ?>
                <div style="text-align:right;"><a href="./news/news.php" class="small_link">Plus de news...</a></div>
                <div class="text_center"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        <div class="news_container" style="float:right;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="articles_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/articles/syndication.php'); ?>
                <h3 class="title valign_middle">Dossiers</h3>
            </div>
            <div class="news_content">
                <?php
                    load_module_lang('articles');
                    echo Feed::get_parsed('articles', DEFAULT_FEED_NAME, 0, array('L_READ' => $LANG['read_feed'], 'L_POSTED_ON' => $LANG['posted_on'], 'L_ON' => $LANG['on']), 3);
                ?>
                <div style="text-align:right;"><a href="./articles/articles.php" class="small_link">Tous les Dossiers...</a></div>
                <div class="spacer"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
		
        <div class="news_container" style="float:left;width:760px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle">Le projet PHPBoost</h3>
            </div>
            <div class="news_content">
            <img src="http://www.phpboost.com/upload/boostor_mini2.jpg" class="img_right" alt="" />
                PHPBoost est un CMS (<em>Content Managing System</em> ou <em>système de gestion de contenu</em>) <strong>français</strong>. Ce logiciel permet à n'importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.<br />
                PHPBoost est un <strong><a href="http://fr.wikipedia.org/wiki/Logiciel_libre">logiciel libre</a></strong> distribué sous la <a href="http://fr.wikipedia.org/wiki/Licence_publique_g%C3%A9n%C3%A9rale_GNU">licence GPL</a>.<br />

                <br />
                Comme son nom l'indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des opérations dans la base de données. Il s'installe sur un serveur Web et se paramètre à distance.<br />
                <br />
                Comme pour une grande majorité de logiciels libres, la communauté de PHPBoost lui permet d'avoir à la fois une fiabilité importante car beaucoup d'utilisateurs ont testé chaque version et les ont ainsi approuvées. Il bénéficie aussi par ailleurs d'une évolution rapide car nous essayons d'être le plus possible à l'écoute des commentaires et des propositions de chacun. Même si tout le monde ne participe pas à son développement, beaucoup de gens nous ont aidés, rien qu'en nous donnant des idées, nous suggérant des modifications, des fonctionnalités supplémentaires.<br />
                <br />
                Si vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br />
                <ul class="bb_ul">
                    <li class="bb_li">Projet Open Source sous licence GNU/GPL</li>
                    <li class="bb_li">Code XHTML 1.0 strict et sémantique</li>
                    <li class="bb_li">Multilangue</li>
                    <li class="bb_li">Facilement personnalisable grâce aux thèmes et templates</li>
                    <li class="bb_li">Gestion fine des droits et des groupes multiples pour chaque utilisateur</li>
                    <li class="bb_li">Url rewriting</li>
                    <li class="bb_li">Installation et mise à jour automatisées des modules et du noyau</li>
                    <li class="bb_li">Aide au développement de nouveaux modules grâce au framework de PHPBoost</li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>

        <div class="news_container" style="float:left;width:760px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle">Le site du moment</h3>
            </div>
            <div class="news_content">
                <h3 class="sub_title">Anorcri</h3>
				<br />
				<p>
					<a href="http://www.anorcri.com" title="Anorcri"><img src="./upload/anorcri_capture.jpg" class="img_right" alt="" /></a>
					Le site <strong>ANORCRI</strong> est un site associatif (association est régie par les articles 21 à 79-III du Code Civil Local maintenu en vigueur dans les départements du Bas-Rhin, Haut-Rhin et Moselle) mis en place en 2002 afin de donner la parole aux personnes adultes souffrant de troubles alimentaires (anorexie, boulimie, hyperphagie...).
					<br />
	On y trouve de nombreuses informations, des adresses de centres hospitaliers, des ouvrages, une liste d'associations, des témoignages, des vidéos dont celle de l'auteur du site (vidéo de 2003 qui avait énormément touché et choqué les téléspectateurs, l'anorexie étant encore trop souvent associée à l'adolescence).
					<br />
					Le forum offre un espace d'écoute et de soutien mais c'est également un lieu de partage du quotidien.
					<br /><br />
					Depuis plus de deux ans maintenant, PHPBoost offre un support de qualité au site ANORCRI. L'équipe de PHPBoost a également réalisé différents thèmes graphiques pour le site.
					<br /><br />
					Pour visiter le site : <a href="http://www.anorcri.com" title="Anorcri">www.anorcri.com</a>
				</p>
				<div style="text-align:center;">
					<a href="./pages/references">Voir les autres sites de référence</a>
				</div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="download_feeds_modules" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/download/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers Modules</h3>
            </div>
            <div class="news_content">
                <?php
                    load_module_lang('download');
                    global $DOWNLOAD_LANG;
                    $dw_langs = array('L_READ' => $DOWNLOAD_LANG['read_feed'], 'L_POSTED_ON' => $LANG['on']);
                    echo Feed::get_parsed('download', DEFAULT_FEED_NAME, 24, $dw_langs, 5);
                ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="download_feeds_themes" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/download/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers Thèmes</h3>
            </div>
            <div class="news_content">
                <?php
					echo Feed::get_parsed('download', DEFAULT_FEED_NAME, 23, $dw_langs, 5);
                ?>
                <div class="spacer"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="forum_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/forum/syndication.php'); ?>
                <h3 class="title valign_middle">Derniers sujets du forum</h3>
            </div>
            <div class="news_content">
                <?php echo Feed::get_parsed('forum', DEFAULT_FEED_NAME, 0); ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span id="wiki_feeds" style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                <?php echo get_feed_menu('/wiki/syndication.php'); ?>
                <h3 class="title valign_middle">Dernières articles de la documentation</h3>
            </div>
            <div class="news_content">
                <?php echo Feed::get_parsed('wiki', DEFAULT_FEED_NAME, 0); ?>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>

<?php

require_once('./kernel/footer.php');

?>