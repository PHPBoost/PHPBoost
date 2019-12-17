<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 4.0 - 2013 06 30
*/

####################################################
#                     English                      #
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
For more information about the features of this module, feel free to ask questions on the <a href="https://www.phpboost.com/forum/">support forum</a>.';
?>
