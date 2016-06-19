<?php
/*##################################################
 *                             config.php
 *                            -------------------
 *   begin                : June 30, 2013
 *   copyright            : (C) 2013 j1.seth
 *   email                : j1.seth@phpboost.com
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

$lang['wiki_name'] = 'Wiki ' . GeneralConfig::load()->get_site_name();
$lang['index_text'] = 'Welcome on the wiki module!<br /><br />
Here are some tips for a good start with this module :<br /><br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, go to the <a href="' . WikiUrlBuilder::configuration()->relative() . '">administration module</a></li>
<li class="formatter-li">To create categories, <a href="' . WikiUrlBuilder::add_category()->relative() . '">click here</a></li>
<li class="formatter-li">To create articles, <a href="' . WikiUrlBuilder::add()->relative() . '">click here</a></li>
</ul><br /><br />
To customize the home page of this module, <a href="' . WikiUrlBuilder::configuration()->relative() . '">click here</a>.<br /><br />
For more information about the features of this module, feel free to ask questions on the <a href="http://www.phpboost.com/forum/">support forum</a>.';
?>
