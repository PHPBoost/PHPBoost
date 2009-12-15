<?php
/*##################################################
 *                               upload_popup.php
 *                            -------------------
 *   begin                : July, 09 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
define('TITLE', $LANG['files_management']);
require_once('../kernel/header_no_display.php');

$id = retrieve(GET, 'id', 0);
if (!empty($id))
{
	$basedir = '../upload/';
	$info_file = $Sql->query_array(PREFIX . "upload", "id", "path", "type", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	if (!empty($info_file['id']))
	{
		switch ($info_file['type'])
		{
			//Images
			case 'jpg':
			header('Content-type: image/jpeg');
			@readfile($basedir . $info_file['path']);
			break;
			case 'png':
			header('Content-type: image/png');
			@readfile($basedir . $info_file['path']);
			break;
			case 'gif':
			header('Content-type: image/gif');
			@readfile($basedir . $info_file['path']);
			break;
			case 'bmp':
			header('Content-type: image/bmp');
			@readfile($basedir . $info_file['path']);
			break;
			case 'svg':
			header("Content-type: image/svg+xml");
			@readfile($basedir . $info_file['path']);
			break;
			//Sons
			case 'mp3':
			echo '<br />
				<object type="application/x-shockwave-flash" data="../kernel/data/dewplayer.swf?son=' . $basedir . $info_file['path'] . '" width="200" height="20">
				<param name="allowScriptAccess" value="never" />
				<param name="play" value="true" />
				<param name="movie" value="../kernel/data/dewplayer.swf?son=' . $basedir . $info_file['path'] . '" />
				<param name="menu" value="false" />
				<param name="quality" value="high" />
				<param name="scalemode" value="noborder" />
				<param name="wmode" value="transparent" />
				<param name="bgcolor" value="#FFFFFF" />
			</object>';
			break;
		}
	}
}

require_once('../kernel/footer_no_display.php');

?>