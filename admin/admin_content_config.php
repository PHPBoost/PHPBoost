<?php
/*##################################################
 *                               admin_content_config.php
 *                            -------------------
 *   begin                : July 4 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

if( !empty($_POST['submit'])  )
{

	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$template = new Template('admin/admin_content_config.tpl');
	
	$template->assign_vars(array());
	
	$template->parse(); // traitement du modele	
}

require_once('../kernel/admin_footer.php');

?>