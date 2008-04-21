<?php
header('Content-type: text/html; charset=iso-8859-15');

define('ERROR_REPORTING', E_ALL | E_NOTICE);
@error_reporting(ERROR_REPORTING);

$lang = !empty($_GET['lang']) ? trim($_GET['lang']) : 'french';
if( !@include_once('lang/' . $lang . '/install_' . $lang . '.php') )
	include_once('lang/french/install.php');
$chmod = !empty($_GET['chmod']) ? true : false;
$db = !empty($_GET['db']) ? true : false;

if( $chmod )
{
	//Mise à jour du cache.
	@clearstatcache();
	
	$chmod_dir = array('../', '../cache', '../cache/backup', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../includes/auth', '../lang', '../templates', '../upload');
	
	//Vérifications et le cas échéants changements des autorisations en écriture.
	foreach($chmod_dir as $dir)
	{
		$is_writable = $is_dir = true;
		if( file_exists($dir) && is_dir($dir) )
		{
			if( !is_writable($dir) )
				$is_writable = (@chmod($dir, 0777)) ? true : false;			
		}
		else
			$is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;
		$found = ($is_dir === true) ? '<div class="success_block">' . $LANG['existing'] . '</div>' : '<div class="failure_block">' . $LANG['unexisting'] . '</div>';
		$writable = ($is_writable === true) ? '<div class="success_block">' . $LANG['writable'] . '</div>' : '<div class="failure_block">' . $LANG['unwritable'] . '</div>';
		
		echo '<dl>
			<dt><label>' . str_replace('../' , '', $dir) . '</label></dt>
			<dd>
				' . $found . '
				' . $writable . '
			</dd>								
		</dl>';
	}

}
elseif( $db )
{
	//Assignation des variables et erreurs
	$array_fields = array('dbms', 'host', 'login', 'password', 'database');
	foreach( $array_fields as $field_name )
	{
		if( !empty($_POST[$field_name]) || $field_name == 'password' )
			$$field_name = trim($_POST[$field_name]);
		else
			die('<div class="warning">' . sprintf($LANG['empty_field'], $LANG['field_' . $field_name]) . '</div>');
	}
	//Tentative de connexion
	if( !@include_once('../includes/framework/db/' . $dbms . '.class.php') )
		die('<div class="error">' . $LANG['db_error_dbms'] . '</div>');
	$Sql = new Sql(false);
	//Connexion
	$result = @$Sql->Sql_connect($host, $login, $password);
	if( !$result )
		die('<div class="error">' . $LANG['db_error_connexion'] . '</div>');
	//Sélection de la base de données
	if( !@$Sql->Sql_select_db($database, $result) )
		die('<div class="warning">' . $LANG['db_error_selection'] . '</div>');
	//Déconnexion
	$Sql->Sql_close();
	echo '<div class="success">' . $LANG['db_success'] . '</div>';
}

?>