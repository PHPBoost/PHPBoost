<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright          : (C) 2007 CrowkaiT
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

?>
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left">
                    <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)" onmouseout="HideSyndication(this)">
                        <a href="#" title="{L_SYNDICATION}">
                            <img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" />
                        </a>
                    </span>&nbsp;
                    <h3 class="title valign_middle">Dernières news</h3>
                </div>
                <div style="float:right"></div>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="cache/syndication/news.js"></script>
                <script type="text/javascript">
                <!--
                    document.write(get_news_feed(10));
                -->
                </script>
                <div class="text_center"></div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Dossiers</h3>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="cache/syndication/articles.js"></script>
                <script type="text/javascript">
                <!--
                    document.write(get_articles_feed(4));
                -->
                </script>
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
                <img src="http://www.phpboost.com/upload/boostor_mini.jpg" class="img_right" alt="" />
                PHPBoost est un CMS (<em>Content Managing System</em> ou <em>système de gestion de contenu</em>) <strong>français</strong>. Ce logiciel permet à n'importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.<br>
PHPBoost est un <strong><a href="http://fr.wikipedia.org/wiki/Logiciel_libre">logiciel libre</a></strong> distribué sous la <a href="http://fr.wikipedia.org/wiki/Licence_publique_g%C3%A9n%C3%A9rale_GNU">licence GPL</a>.<br>

<br>
Comme son nom l'indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des opérations dans la base de données. Il s'installe sur un serveur Web et se paramètre à distance.<br>
<br>
Comme pour une grande majorité de logiciels libres, la communauté de PHPBoost lui permet d'avoir à la fois une fiabilité importante car beaucoup d'utilisateurs ont testé chaque version et les ont ainsi approuvées. Il bénéficie aussi par ailleurs d'une évolution rapide car nous essayons d'être le plus possible à l'écoute des commentaires et des propositions de chacun. Même si tout le monde ne participe pas à son développement, beaucoup de gens nous ont aidés, rien qu'en nous donnant des idées, nous suggérant des modifications, des fonctionnalités supplémentaires.<br>
<br>
Si vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br>
<ul class="bb_ul"><li class="bb_li">Projet Open Source sous licence GNU/GPL
</li><li class="bb_li">Code XHTML 1.0 strict et sémantique
</li><li class="bb_li">Multilangue
</li><li class="bb_li">Facilement personnalisable grâce aux thèmes et templates
</li><li class="bb_li">Gestion fine des droits et des groupes multiples pour chaque utilisateur
</li><li class="bb_li">Url rewriting
</li><li class="bb_li">Installation et mise à jour automatisées des modules et du noyau
</li><li class="bb_li">Aide au développement de nouveaux modules grâce au framework de PHPBoost</li>
            </ul></div>
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
                <a href="http://www.mussotrail.com" title="Mussotrail"><img src="./upload/theme.jpg" class="img_right" alt="" /></a>
                <h3 class="sub_title">Mussotrail</h3>
				<p>
					<br />
					Le site du Mussotrail, est un site d'un team de BMX et VTT évoluant dans le sud de la france.
					<br />
					Il s'agit du site à l'origine de PHPBoost, le projet est issu du développement de ce site.
					<br />
					Sa personnalisation poussée en font une référence en terme de sites utilisant le moteur PHPBoost.
					<br /><br /><br />
					Pour visiter le site: <a href="http://www.mussotrail.com" title="Mussotrail">Mussotrail.com</a>
				</p>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Modules</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Bannière</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Team</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">23/04</span> <a href="">Agenda</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">22/04</span> <a href="">Match</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">20/04</span> <a href="">Wiki</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">18/04</span> <a href="">Blogs</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Bloc notes</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Bugshack</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Todo</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Partenariats</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Sitemap</a></li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Thèmes</h3>
            </div>
            <div class="news_content">
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701143224.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Medieval</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701280722.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Heroic fantasy</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701284286.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Warcraft III</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Voir</a></p>
                </div>
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
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers sujets du forum</h3>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="./cache/rss_forum.html"></script>  
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="./forum/syndication.php" title="Syndication"><img class="valign_middle" src="./templates/<?php echo $CONFIG['theme']; ?>/images/rss.png" alt="Syndication" title="Syndication" /></a> Dernières vidéos</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Bannière</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Ajouter une news</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Ajouter des photos dans la galerie</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Créer des articles</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">09/03</span> <a href="">Ajouter des menus</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Fonctionnalités avancées du BBcode</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">28/02</span> <a href="">Sauvegarder votre base de donnnées</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Transférer des fichiers sur votre FTP</a></li>
                    <li><img src="./templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Installer PHPBoost</a></li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>

<?php

require_once('./kernel/footer.php');

?>