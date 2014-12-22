<?php

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../pages/pages_begin.php');
require_once('../kernel/header_no_display.php');

$id_cat = retrieve(POST, 'id_cat', 0);
$select_cat = !empty($_GET['select_cat']) ? true : false;
$selected_cat = retrieve(POST, 'selected_cat', 0);
$display_select_link = !empty($_GET['display_select_link']) ? 1 : 0;
$open_cat = retrieve(POST, 'open_cat', 0);
$root = !empty($_GET['root']) ? 1 : 0;

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

//Listage des répertoires dont le répertoire parent est connu
if ($id_cat != 0)
{	
	echo '<ul>';
	//On sélectionne les répetoires dont l'id parent est connu
	$result = PersistenceContext::get_querier()->select("SELECT c.id, p.title, p.encoded_title, p.auth
	FROM " . PREFIX . "pages_cats c
	LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
	WHERE c.id_parent = :id
	ORDER BY title ASC", array(
		'id' => $id_cat
	));
	while ($row = $result->fetch())
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		//Vérification de l'autorisation d'éditer la page
		if (($special_auth && AppContext::get_current_user()->check_auth($row['auth'], READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
		{
			//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
			$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "pages_cats", 'WHERE id_parent=:id_parent', array('id_parent' => $row['id']));
			//Si cette catégorie contient des sous catégories, on propose de voir son contenu
			if ($sub_cats_number > 0)
				echo '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $row['id'] . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="fa fa-plus-square-o" id="img2_' . $row['id'] . '"></i><i class="fa fa-folder" id="img_' . $row['id'] . '"></i></a><a id="class_' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');">' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></span></li>';
			else //Sinon on n'affiche pas le "+"
				echo '<li class="sub"><a id="class_' . $row['id'] . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $row['id'] . ');"><i class="fa fa-folder"></i>' . $row['title'] . '</a></li>';
		}
	}
	$result->dispose();
	echo '</ul>';
}
//Retour de la localisation du dossier
elseif ($select_cat && empty($open_cat) && $root == 0)
{
	if ($selected_cat > 0)
	{
		$localisation = array();
		$Cache->load('pages');
		$id = $selected_cat; //Premier id
		do
		{
			$localisation[] = $_PAGES_CATS[$id]['name'];
			$id = (int)$_PAGES_CATS[$id]['id_parent'];
		}	
		while ($id > 0);
		$localisation = array_reverse($localisation);
		echo implode(' / ', $localisation);
	}
	else
	{
		load_module_lang('pages');
		echo $LANG['pages_no_selected_cat'];
	}
}
elseif (!empty($open_cat) || $root == 1)
{
	$open_cat = $root == 1 ? 0 : $open_cat;
	$return = '<ul>';
	//Liste des catégories dans cette catégorie
	foreach ($_PAGES_CATS as $key => $value)
	{
		if ($value['id_parent'] == $open_cat)
		{
			//Autorisation particulière ?
			$special_auth = !empty($value['auth']);
			//Vérification de l'autorisation d'éditer la page
			if (($special_auth && AppContext::get_current_user()->check_auth($value['auth'], READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
			{
				$return .= '<li><a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);"><i class="fa fa-folder"></i>' . $value['name'] . '</a></li>';
			}
		}
	}
	$result = PersistenceContext::get_querier()->select("SELECT title, id, encoded_title, auth
	FROM " . PREFIX . "pages
	WHERE id_cat = :id
	ORDER BY is_cat DESC, title ASC", array(
		'id' => $open_cat
	));
	while ($row = $result->fetch())
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		//Vérification de l'autorisation d'éditer la page
		if (($special_auth && AppContext::get_current_user()->check_auth(unserialize($row['auth']), READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
		{
			$return .= '<li><a href="' . PATH_TO_ROOT . url('/pages/pages.php?title=' . $row['encoded_title'], '/pages/' . $row['encoded_title']) . '"><i class="fa fa-file"></i>' . $row['title'] . '</a></li>';
		}
	}
	$result->dispose();
	$return .= '</ul>';
	echo $return;
}


require_once('../kernel/footer_no_display.php');
?>