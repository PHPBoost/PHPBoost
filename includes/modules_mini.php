<?php
/*##################################################
 *                                module_mini.php
 *                            -------------------
 *   begin                : April 06, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHP_BOOST') !== true)	exit;

if( @!include('../cache/modules_mini.php') )
{
	//Régénération du fichier
	$code = '';
	$result = $sql->query_while("SELECT name, code, status, secure 
	FROM ".PREFIX."modules_mini
	WHERE activ = 1
	ORDER BY status, idmod", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( $row['status'] == '0' )
			$code .= 'if( $BLOCK_top && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . $row['code'] . '}' . "\n";
		else
			$code .= 'if( $BLOCK_bottom && $session->data[\'level\'] >= ' . $row['secure'] . ' ){' . $row['code'] . '}' . "\n";
	}
	$sql->close($result);

	$file_path = '../cache/modules_mini.php';
	if( is_file($file_path) )
		@delete_file($file_path); //Supprime le fichier s'il existe déjà
	$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
	@fwrite($handle, "<?php\n" . $code . "\n?>");
	@fclose($handle);
	
	//Il est l'heure de vérifier si la génération a fonctionnée.
	if( !is_file($file_path) && filesize($file_path) == '0' )
		$errorh->error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
		
	//On inclue une nouvelle fois
	if( @!include('../cache/module_mini.php') )
		$errorh->error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
}

?>