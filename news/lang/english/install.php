<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 07
 * @since       PHPBoost 4.0 - 2012 04 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['cat.name'] = 'Test';
$lang['cat.description'] = 'Test category';
$lang['news.title'] = 'Your site within PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version();
$lang['news.content'] = 'Your PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' website is now installed and running. To help you build your website,
each module home has a message to guide you through its configuration. We strongly recommand to do the followings :  <br />
<br />
<h2 class="formatter-title">Delete the "install" folder</h2><br />
<br />
For security reasons, you must delete the entire "install" folder located in the PHPBoost root directory.
Otherwise, some people may try to re-install the software and in that case you may lose datas.
<br />
<h2 class="formatter-title">Manage your website</h2><br />
<br />
Access the <a href="' . UserUrlBuilder::administration()->relative() . '">Administration Panel</a> to set up your website as you wish.
To do so : <br />
<br />
<ul class="formatter-ul">
<li class="formatter-li"><a href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">Put your website under maintenance</a> and you won\'t be disturbed while you\'re working on it.
</li><li class="formatter-li">Now\'s the time to setup the <a href="' . AdminConfigUrlBuilder::general_config()->relative() . '">main configurations</a> of the website.
</li><li class="formatter-li"><a href="' . AdminModulesUrlBuilder::list_installed_modules()->relative() . '">Configure the installed modules</a> and give them access rights (If you have not installed the complete package, all modules are available on the <a href="https://www.phpboost.com/download/">PHPBoost website</a> in the resources section.
</li><li class="formatter-li"><a href="' . AdminContentUrlBuilder::content_configuration()->relative() . '">Choose the default content language formatting</a>.
</li><li class="formatter-li"><a href="' . AdminMembersUrlBuilder::configuration()->relative() . '">Configure the members settings</a>.
</li><li class="formatter-li"><a href="' . AdminThemeUrlBuilder::list_installed_theme()->relative() . '">Choose the website style</a> to change the look of your site (You can find more styles on the <a href="https://www.phpboost.com/download/">PHPBoost website</a> in the resources section.
</li><li class="formatter-li">Before giving back access to your members, take time to add content to your website!
</li><li class="formatter-li">Finally, Finally, <a href="' . AdminMaintainUrlBuilder::maintain()->relative() . '">put your site online</a> in order to restore access to your site to your visitors.<br />
</li></ul><br />
<br />
<h2 class="formatter-title">What should I do if I have problems ?</h2><br />
<br />
Feel free to consult the <a href="https://www.phpboost.com/wiki/">PHPBoost documentation</a> or to ask your question on the <a href="https://www.phpboost.com/forum/">forum</a> for assistance. As the English community is still young, we strongly recommend that you use the second solution.<br /> <br />
<br />
<p class="float-right">The PHPBoost Team thanks you for using its software to create your Web site!</p>';
?>
