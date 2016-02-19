<?php
/*##################################################
 *                             install.php
 *                            -------------------
 *   begin                : April 09, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						English						#
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
Access the <a href="' . UserUrlBuilder::administration()->rel() . '">Administration Panel</a> to set up your website as you wish.
To do so : <br />
<br />
<ul class="formatter-ul">
<li class="formatter-li"><a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Put your website under maintenance</a> and you won\'t be disturbed while you\'re working on it.
</li><li class="formatter-li">Now\'s the time to setup the <a href="' . AdminConfigUrlBuilder::general_config()->rel() . '">main configurations</a> of the website.
</li><li class="formatter-li"><a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Configure the installed modules</a> and give them access rights (If you have not installed the complete package, all modules are available on the <a href="http://www.phpboost.com/download/">PHPBoost website</a> in the resources section.
</li><li class="formatter-li"><a href="' . AdminContentUrlBuilder::content_configuration()->rel() . '">Choose the default content language formatting</a>.
</li><li class="formatter-li"><a href="' . AdminMembersUrlBuilder::configuration()->rel() . '">Configure the members settings</a>.
</li><li class="formatter-li"><a href="' . AdminThemeUrlBuilder::list_installed_theme()->rel() . '">Choose the website style</a> to change the look of your site (You can find more styles on the <a href="http://www.phpboost.com/download/">PHPBoost website</a> in the resources section.
</li><li class="formatter-li">Before giving back access to your members, take time to add content to your website!
</li><li class="formatter-li">Finally, Finally, <a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">put your site online</a> in order to restore access to your site to your visitors.<br />
</li></ul><br />
<br />
<h2 class="formatter-title">What should I do if I have problems ?</h2><br />
<br />
Do not hesitate to consult the <a href="http://www.phpboost.com/wiki/">PHPBoost documentation</a> or ask your question on the <a href="http://www.phpboost.com/forum/">forum</a> for assistance. As the English community is still young, we strongly recommend that you use the second solution.<br /> <br />
<br />
<p class="float-right">The PHPBoost Team thanks you for using its software to create your Web site!</p>';
?>