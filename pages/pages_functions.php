<?php
/*##################################################
 *                              pages_functions.php
 *                            -------------------
 *   begin                : August 15, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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

if (defined('PHPBOOST') !== true)	exit;

//Catégories (affichage si on connait la catégorie et qu'on veut reformer l'arborescence)
function display_cat_explorer($id, &$cats, $display_select_link = 1)
{
	global $_PAGES_CATS;
		
	if ($id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des catégories afin de savoir quelle catégorie développer
		do
		{
			$cats[] = (int)$_PAGES_CATS[$id_cat]['id_parent'];
			$id_cat = (int)$_PAGES_CATS[$id_cat]['id_parent'];
		}	
		while ($id_cat > 0);
	}
	

	//Maintenant qu'on connait l'arborescence on part du début
	$cats_list = '<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">' . show_cat_contents(0, $cats, $id, $display_select_link) . '</ul>';
	
	//On liste les catégories ouvertes pour la fonction javascript
	$opened_cats_list = '';
	foreach ($cats as $key => $value)
	{
		if ($key != 0)
			$opened_cats_list .= 'cat_status[' . $key . '] = 1;' . "\n";
	}
	return '<script type="text/javascript">
	<!--
' . $opened_cats_list . '
	-->
	</script>
	' . $cats_list;
	
}

//Fonction récursive pour l'affichage des catégories
function show_cat_contents($id_cat, $cats, $id, $display_select_link)
{
	global $_PAGES_CATS, $Sql;
	
	$Template = new FileTemplate('pages/post.tpl');
	$module_data_path = $Template->get_pictures_data_path();
	
	$line = '';
	foreach ($_PAGES_CATS as $key => $value)
	{
		//Si la catégorie appartient à la catégorie explorée
		if ($value['id_parent']  == $id_cat)
		{
			if (in_array($key, $cats)) //Si cette catégorie contient notre catégorie, on l'explore
			{
				$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $module_data_path . '/images/minus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_pictures_data_path() . '/images/opened_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '">
				<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;padding-left:30px;">'
				. show_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
				$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $key . "'", __LINE__, __FILE__);
				//Si cette catégorie contient des sous catégories, on propose de voir son contenu
				if ($sub_cats_number > 0)
					$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $module_data_path . '/images/plus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_pictures_data_path() . '/images/closed_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '"></span></li>';
				else //Sinon on n'affiche pas le "+"
					$line .= '<li style="padding-left:17px;"><img src="' . $module_data_path . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'pages_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span></li>';
			}
		}
	}
	return "\n" . $line;
}

//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
function pages_find_subcats(&$array, $id_cat)
{
	global $_PAGES_CATS;
	//On parcourt les catégories et on détermine les catégories filles
	foreach ($_PAGES_CATS as $key => $value)
	{
		if ($value['id_parent'] == $id_cat)
		{
			$array[] = $key;
			//On rappelle la fonction pour la catégorie fille
			pages_find_subcats($array, $key);
		}
	}
}

//Fonction "parse" pour les pages laissant passer le html tout en remplaçant les caractères spéciaux par leurs entités html correspondantes
function pages_parse($contents)
{
	$contents = FormatingHelper::strparse($contents);
	$contents = preg_replace('`\[link=([a-z0-9+#-]+)\](.+)\[/link\]`isU', '<a href="/pages/$1">$2</a>', $contents);
	
	return (string) $contents;
}

//Fonction unparse
function pages_unparse($contents)
{
	$contents = link_unparse($contents);
	return FormatingHelper::unparse($contents);
}

//Second parse -> à l'affichage
function pages_second_parse($contents)
{
	if (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled()) //Pas de rewriting	
	{
			$contents = preg_replace('`<a href="/pages/([a-z0-9+#-]+)">(.*)</a>`sU', '<a href="/pages/pages.php?title=$1">$2</a>', $contents);
	}
	$contents = FormatingHelper::second_parse($contents);
	return $contents;
}

//On remplace la balise link
function link_unparse($contents)
{
	$contents = is_array($contents) ? $contents[0] : $contents;
	return preg_replace('`<a href="(?:/pages/)?([a-z0-9+#-]+)">(.*)</a>`sU', "[link=$1]$2[/link]", $contents);
}

?>