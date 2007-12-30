<?php
header('Content-type: text/html; charset=iso-8859-15');

require_once('../includes/begin.php');
define('TITLE', 'Utilisation d\'AJAX');
require_once('../includes/header_no_display.php');

$id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = !empty($_POST['selected_cat']) ? numeric($_POST['selected_cat']) : 0;
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = !empty($_POST['open_cat']) ? numeric($_POST['open_cat']) : 0;
$root = !empty($_GET['root']) ? 1 : 0;


//Listage des répertoires dont le répertoire parent est connu
if( $id_cat != 0 )
{
	echo '<ul style="margin:0;padding:0;list-style-type:none;padding-left:30px;">';
	//On sélectionne les répetoires dont l'id parent est connu
	$result = $sql->query_while("SELECT id, id_parent, name
	FROM ".PREFIX."upload_cat
	WHERE id_parent = " . $id_cat . "
	ORDER BY name ASC", __LINE__, __FILE__);
	$nbr_subcats = $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE id_parent = '" . $id_cat. "'", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
		$sub_cats_number = $sql->query("SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
		//Si cette catégorie contient des sous catégories, on propose de voir son contenu
		if( $sub_cats_number > 0 )
			echo '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . $CONFIG['theme'] . '/images/upload/plus.png" alt="" id="img2_' . $row['id'] . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="../templates/' . $CONFIG['theme'] . '/images/upload/closed_cat.png" alt="" id="img_' . $row['id'] . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>';
		else //Sinon on n'affiche pas le "+"
			echo '<li style="padding-left:17px;"><img src="../templates/' . $CONFIG['theme'] . '/images/upload/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['name'] . '</a></span></li>';
	}
	$sql->close($result);
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif( $select_cat && empty($open_cat) && $root == 0 )
{
	if( $selected_cat > 0)
	{
		$localisation = array();
		$cache->load_file('wiki');
		$id = $selected_cat; //Permier id
		do
		{
			$localisation[] = $_WIKI_CATS[$id]['name'];
			$id = (int)$_WIKI_CATS[$id]['id_parent'];
		}	
		while( $id > 0 );
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('wiki', $CONFIG['lang']);
		echo $LANG['wiki_no_selected_cat'];
	}
}

require_once('../includes/footer_no_display.php');
?>