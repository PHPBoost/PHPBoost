        <script type="text/javascript">
        <!--
        function Confirm() {
            return confirm("{L_ALERT_DELETE_NEWS}");
        }
        function ShowSyndication(element) {
            alert
            if ( element.firstChild.nextSibling.nextSibling.nextSibling.style.visibility == 'hidden' )
                element.firstChild.nextSibling.nextSibling.nextSibling.style.visibility = 'visible';
            else
                element.firstChild.nextSibling.nextSibling.nextSibling.style.visibility = 'hidden';
        }
        -->
        </script>
        
        # IF C_NEWS_EDITO #
        <div class="news_container">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left;padding-left:30px;"><h3 class="title">{edito.TITLE}</h3></div>
                <div style="float:right;">{edito.EDIT}</div>
            </div>
            <div class="news_content">
                <img src="../templates/phpboost/news/images/phpboost_box_v2_mini.jpg" alt="PHPBoost 2.0" class="float_right" />
                <img src="../templates/phpboost/news/images/phpboost_version.jpg" alt="PHPBoost 2.0" />
                <br />
                &nbsp;&nbsp;{edito.CONTENTS}
            </div>
            
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        # ENDIF #
        
        # IF C_NEWS_NO_AVAILABLE #
        <div class="news_container">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left;padding-left:30px;"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a></div>
                <div style="float:right;"><h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
            </div>  
            <div class="news_content">
                <p class="text_strong text_center">{L_NO_NEWS_AVAILABLE}</p>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        # ENDIF #
        
        # IF C_NEWS_BLOCK #
        {START_TABLE_NEWS}
        # START news #
        
        {news.NEW_ROW}
        <div class="news_container">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)" onmouseout="ShowSyndication(this)">
                    <a href="#" title="Syndication">
                        <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                    </a>
                    <div name="syndication_choice" style="position:absolute;overflow:visible;width:0px;height:0px;visibility:hidden;">
                        <ul style="width:60px;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;background:#ffffff;border:1px #ccccff solid;">
                            <li><a href="../news/syndication.php?feed=rss" title="RSS">RSS</a></li>
                            <li><a href="../news/syndication.php?feed=atom" title="ATOM">ATOM</a></li>
                        </ul>
                    </div> &nbsp;&nbsp;
                    <a class="news_title" href="news{news.U_NEWS_LINK}">{news.TITLE}</a>
                </span>
                <span style="float:right;">{news.COM}{news.EDIT}{news.DEL}</span>
            </div>
            <div class="news_content">
                {news.IMG}
                {news.ICON}
                {news.CONTENTS}
                <br /><br />
                {news.EXTEND_CONTENTS}
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom">
                <span style="float:left"><a class="small_link" href="../member/member{news.U_MEMBER_ID}">{news.PSEUDO}</a></span>
                <span style="float:right">{news.DATE}</span>
            </div>
        </div>
        # INCLUDE handle_com #
        # END news #
        
        {END_TABLE_NEWS}
        
        # IF C_NEWS_NAVIGATION_LINKS #
        <div style="width:90%;padding:20px;margin:auto;margin-top:-15px;">
            <span style="float:left;">{U_PREVIOUS_NEWS}</span>
            <span style="float:right;">{U_NEXT_NEWS}</span>
        </div>
        # ENDIF #
        
        <div class="text_center">{PAGINATION}</div>
        <div class="text_center">{ARCHIVES}</div>
        # ENDIF #
        
        
        # IF C_NEWS_LINK #
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
                <div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
            </div>  
            <div class="news_content">
                {START_TABLE_NEWS}
                # START list #
                    {list.NEW_ROW}
                        <li><img src="../templates/{THEME}/images/li.png" alt="" /> {list.ICON} <span class="text_small">{list.DATE}</span> <a href="{list.U_NEWS}">{list.TITLE}</a></li>
                # END list #
                {END_TABLE_NEWS}    
                
                <br />
                <div class="text_center">{PAGINATION}</div>
                <div class="text_center">{ARCHIVES}</div>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>  
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="../forum/syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> Dossiers</h3>
            </div>
            <div class="news_content">
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701299648.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Comment sécuriser votre site</a>
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701295144.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">La programmation sous PHPBoost</a>   
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
                </div>
                <div class="spacer"></div>
                
                <div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701294702.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
                <div style="float:left;width:250px;padding-left:6px;">
                    <a href="">Installer un nouveau module</a>  
                    <p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
                </div>
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
</li><li class="bb_li">Facilement personnalisable grâce aux thêmes et templates
</li><li class="bb_li">Gestion fine des droits et des groupes multiples pour chaque utilisateur
</li><li class="bb_li">Url rewriting
</li><li class="bb_li">Installation et mise à jour automatisées des modules et du noyau
</li><li class="bb_li">Aide au développement de nouveaux modules grâce au framework de PHPBoost</li>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:760px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle">Site de la semaine</h3>
            </div>
            <div class="news_content">
                <img src="../templates/phpboost/images/theme.jpg" class="img_right" alt="" />
                    <p>Fusce hendrerit, purus id semper vulputate, massa nunc accumsan arcu, quis faucibus dui libero dictum tortor. Vestibulum ut libero sed leo euismod lobortis. Sed eleifend semper quam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam vitae sapien non neque malesuada fringilla. Fusce accumsan quam ut erat. Curabitur rutrum ligula id orci. Duis et massa non lectus accumsan sagittis. Etiam eget massa eget neque aliquet interdum. Duis accumsan luctus sem. Vestibulum ultricies mollis nisi. Sed ante purus, aliquam quis, cursus vel, lacinia ullamcorper, dui. Quisque feugiat. Morbi nibh. Donec vel ante. Nulla facilisi.</p>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:10px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="../forum/syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Modules</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Bannière</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Team</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">23/04</span> <a href="">Agenda</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">22/04</span> <a href="">Match</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">20/04</span> <a href="">Wiki</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">18/04</span> <a href="">Blogs</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Bloc notes</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Bugshack</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Todo</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Partenariats</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Sitemap</a></li>
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
                <h3 class="title valign_middle"><a href="../forum/syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers Th?mes</h3>
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
                <h3 class="title valign_middle"><a href="../forum/syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> Derniers sujets du forum</h3>
            </div>
            <div class="news_content">
                <script type="text/javascript" src="../cache/rss_forum.html"></script>  
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        <div class="news_container" style="float:left;width:365px;margin-left:30px;">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <h3 class="title valign_middle"><a href="../forum/syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> Dernières vidéos</h3>
            </div>
            <div class="news_content">
                <ul style="margin:0;padding:0;list-style-type:none;">
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">29/04</span> <a href="">Bannière</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">25/04</span> <a href="">Ajouter une news</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">24/03</span> <a href="">Ajouter des photos dans la galerie</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">12/03</span> <a href="">Créer des articles</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">09/03</span> <a href="">Ajouter des menus</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">04/03</span> <a href="">Fonctionnalitès avancées du BBcode</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">28/02</span> <a href="">Sauvegarder votre base de donnnées</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">19/02</span> <a href="">Transférer des fichiers sur votre FTP</a></li>
                    <li><img src="../templates/phpboost/images/li.png" alt="" class="valign_middle" /> <span class="text_small">10/02</span> <a href="">Installer PHPBoost</a></li>
                </ul>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
        
        # ENDIF #
