<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 4.0 - 2013 04 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

####################################################
#                     French                       #
####################################################

$lang['cat.name'] = 'Test';
$lang['cat.description'] = 'Catégorie de test';
$lang['news.title'] = 'Votre site sous PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['news.content'] = 'Votre site boosté par PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' est bien installé. Afin de vous aider à le prendre en main,
l\'accueil de chaque module contient un message pour vous guider dans vos premiers pas. Voici également quelques recommandations supplémentaires que nous vous proposons de lire avec attention : <br />
<br />
<h2 class="formatter-title">N\'oubliez pas de supprimer le répertoire "install"</h2><br />
<br />
Supprimez le répertoire /install à la racine de votre site pour des raisons de sécurité afin que personne ne puisse recommencer l\'installation.<br />
<br />
<h2 class="formatter-title">Administrez votre site</h2><br />
<br />
Accédez au <a href="' . UserUrlBuilder::administration()->relative() . '">panneau d\'administration de votre site</a> afin de le paramétrer comme vous le souhaitez !  Pour cela : <br />
<br />
<ul class="formatter-ul">
<li class="formatter-li"><a href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">Mettez votre site en maintenance</a> en attendant que vous le configuriez à votre guise.
</li><li class="formatter-li">Rendez vous à la <a href="' . AdminConfigUrlBuilder::general_config()->relative() . '">Configuration générale du site</a>.
</li><li class="formatter-li"><a href="' . AdminModulesUrlBuilder::list_installed_modules()->relative() . '">Configurez les modules</a> disponibles et donnez leur les droits d\'accès (si vous n\'avez pas installé le pack complet, tous les modules sont disponibles sur le site de <a href="https://www.phpboost.com/download/">phpboost.com</a> dans la section téléchargement).
</li><li class="formatter-li"><a href="' . AdminContentUrlBuilder::content_configuration()->relative() . '">Choisissez le langage de formatage du contenu</a> par défaut du site.
</li><li class="formatter-li"><a href="' . AdminMembersUrlBuilder::configuration()->relative() . '">Configurez l\'inscription des membres</a>.
</li><li class="formatter-li"><a href="' . AdminThemeUrlBuilder::list_installed_theme()->relative() . '">Choisissez le thème par défaut de votre site</a> pour en changer l\'apparence (vous pouvez en obtenir d\'autres sur le site de <a href="https://www.phpboost.com/download/">phpboost.com</a>).
</li><li class="formatter-li">Avant de donner l\'accès de votre site à vos visiteurs, prenez un peu de temps pour y mettre du contenu.
</li><li class="formatter-li">Enfin <a href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">désactivez la maintenance</a> de votre site afin qu\'il soit visible par vos visiteurs.<br />
</li></ul><br />
<br />
<h2 class="formatter-title">Que faire si vous rencontrez un problème ?</h2><br />
<br />
N\'hésitez pas à consulter <a href="https://www.phpboost.com/wiki/">la documentation de PHPBoost</a> ou à poser vos questions sur le <a href="https://www.phpboost.com/forum/">forum d\'entraide</a>.<br /> <br />
<br />
<p class="float-right">Toute l\'équipe de PHPBoost vous remercie d\'utiliser son logiciel pour créer votre site web !</p>';
?>
