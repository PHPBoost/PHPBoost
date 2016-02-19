<?php
/*##################################################
 *                            install.php
 *                            -------------------
 *   begin                : Ferbruary 21, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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


#####################################################
#                      English			    #
####################################################

$lang = array();

$lang['default.category.name'] = 'First category';
$lang['default.category.description'] = 'Demonstration of an article';
$lang['default.article.title'] = 'How to begin with the articles module';
$lang['default.article.description'] = '';
$lang['default.article.contents'] = 'This brief article will give you some simple tips to take control of this module.<br /> 
<br /> 
<ul class="formatter-ul"> 
<li class="formatter-li">To configure your module, <a href="' . ArticlesUrlBuilder::configuration()->rel() . '">click here</a> 
</li><li class="formatter-li">To add categories: <a href="' . ArticlesUrlBuilder::add_category()->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add an item: <a href="' . ArticlesUrlBuilder::add_article()->rel() . '">click here</a> 
</li></ul> 
<ul class="formatter-ul">
<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a href="http://www.phpboost.com/wiki/bbcode">article</a>)<br /> 
</li></ul><br /> 
<br /> 
For more information, please see the module documentation on the site <a href="http://www.phpboost.com">PHPBoost</a>.<br /> 
<br /> 
<br /> 
Good use of this module.';

?>
