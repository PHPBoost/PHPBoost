<?php
/*##################################################
 *                               newsletter_arch.php
 *                            -------------------
 *   begin                : July 11, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

require_once('../kernel/begin.php');
require_once('../newsletter/newsletter_begin.php');
require_once('../kernel/header_no_display.php');

$id = retrieve(GET, 'id', 0);

if (!empty($id))
{
	$newsletter = $Sql->query_array(PREFIX . 'newsletter_arch', 'type', 'title', 'message', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	if ($newsletter['type'] == 'html')
	{
		$message = stripslashes($newsletter['message']);
		$message = str_replace('<body', '<body onclick = "window.close()" ', $message);
		$message = str_replace('[UNSUBSCRIBE_LINK]', '', $message);
		echo $message;
	}
	elseif ($newsletter['type'] == 'bbcode')
	{
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $LANG['xml_lang'] .'"><head><title>' . $newsletter['title'] . '</title></head><body onclick = "window.close()"><p>' . $newsletter['message'] . '</p></body></html>';
		echo $message;
	}
	else
		exit;
}
else
	exit;

?>