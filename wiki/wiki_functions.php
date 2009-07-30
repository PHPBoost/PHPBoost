<?php
/*##################################################
 *                              wiki_functions.php
 *                            -------------------
 *   begin                : May 6, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if (defined('PHPBOOST') !== true)	exit;

define('WIKI_MENU_MAX_DEPTH', 5);

//Interprétation du BBCode en ajoutant la balise [link]
function wiki_parse(&$var)
{
	//On force le langage de formatage à BBCode
	$content_manager = new ContentFormattingFactory(BBCODE_LANGUAGE);
	$parser = $content_manager->get_parser();
    $parser->set_content($var, MAGIC_QUOTES);
    $parser->parse();
	
    //Parse la balise link
	return preg_replace('`\[link=([a-z0-9+#-]+)\](.+)\[/link\]`isU', '<a href="/wiki/$1">$2</a>', $parser->get_content());
}

//Retour au BBCode en tenant compte de [link]
function wiki_unparse($var)
{
	//Unparse de la balise link
	$var = preg_replace('`<a href="(?:/wiki/)?([a-z0-9+#-]+)">(.*)</a>`sU', "[link=$1]$2[/link]", $var);
	
	//On force le langage de formatage à BBCode
	$content_manager = new ContentFormattingFactory(BBCODE_LANGUAGE);
	$unparser = $content_manager->get_unparser();
    $unparser->set_content($var, PARSER_DO_NOT_STRIP_SLASHES);
    $unparser->parse();
	
	return $unparser->get_content(DO_NOT_ADD_SLASHES);
}

//Fonction de correction dans le cas où il n'y a pas de rewriting (balise link considére par défaut le rewriting activé)
function wiki_no_rewrite($var)
{
	global $CONFIG;
	if ($CONFIG['rewrite'] == 0) //Pas de rewriting	
		return preg_replace('`<a href="([a-z0-9+#-]+)">(.*)</a>`sU', '<a href="wiki.php?title=$1">$2</a>', $var);
	else
		return $var;
}

//Fonction de décomposition récursive (passage par référence pour la variable content qui passe de chaîne à tableau de chaînes (5 niveaux maximum)
function wiki_explode_menu(&$content)
{
	$lines = explode("\n", $content);
	$num_lines = count($lines);
	$max_level_expected = 2;
	
	$list = array();
	
	//We read the text line by line
	$i = 0;
	while ($i < $num_lines)
	{
		for ($level = 2; $level <= $max_level_expected; $level++)
		{
			$matches = array();
			
			//If the line contains a title
			if (preg_match('`^\s*[\-]{' . $level . '}[\s]+(.+)[\s]+[\-]{' . $level . '}(?:<br />)?\s*$`', $lines[$i], $matches))
			{
				$title_name = strip_tags(html_entity_decode($matches[1]));
				
				//We add it to the list
				$list[] = array($level - 1, $title_name);
				//Now we wait one of its children or its brother
				$max_level_expected = min($level + 1, WIKI_MENU_MAX_DEPTH + 1);
				
				//Réinsertion
				$class_level = $level - 1;
				$lines[$i] = '<h' . $class_level . ' class="wiki_paragraph' .  $class_level . '" id="paragraph_' . url_encode_rewrite($title_name) . '">' . htmlspecialchars($title_name) .'</h' . $class_level . '><br />' . "\n";
			}
		}
		$i++;
	}
	
	$content = implode("\n", $lines);
	
	return $list;
}

//Fonction d'affichage récursive
function wiki_display_menu($menu_list)
{
	if (count($menu_list) == 0) //Aucun titre de paragraphe
	{
		return '';
	}
	
	$menu = '';
	$last_level = 0;
		
	foreach ($menu_list as $title)
	{
		$current_level = $title[0];
		
		$title_name = stripslashes($title[1]);		
		$title_link = '<a href="#paragraph_' . url_encode_rewrite($title_name) . '">' . htmlspecialchars($title_name) . '</a>';
		
		if ($current_level > $last_level)
		{
			$menu .= '<ol class="wiki_list_' . $current_level . '"><li>' . $title_link;
		}
		elseif ($current_level == $last_level)
		{
			$menu .= '</li><li>' . $title_link;
		}
		else
		{
			if (substr($menu, strlen($menu) - 4, 4) == '<li>')
			{
				$menu = substr($menu, 0, strlen($menu) - 4);
			}
			$menu .= str_repeat('</li></ol>', $last_level - $current_level) . '</li><li>' . $title_link;
		}
		$last_level = $title[0];
	}
	
	//End
	if (substr($menu, strlen($menu) - 4, 4) == '<li>')
	{
		$menu = substr($menu, 0, strlen($menu) - 4);
	}
	$menu .= str_repeat('</li></ol>', $last_level);
	
	return $menu;
}

//Catégories (affichage si on connait la catégorie et qu'on veut reformer l'arborescence)
function display_cat_explorer($id, &$cats, $display_select_link = 1)
{
	global $_WIKI_CATS;
		
	if ($id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des catégories afin de savoir quelle catégorie développer
		do
		{
			$cats[] = (int)$_WIKI_CATS[$id_cat]['id_parent'];
			$id_cat = (int)$_WIKI_CATS[$id_cat]['id_parent'];
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
	global $_WIKI_CATS, $Sql, $Template;
	$line = '';
	foreach ($_WIKI_CATS as $key => $value)
	{
		//Si la catégorie appartient à la catégorie explorée
		if ($value['id_parent']  == $id_cat)
		{
			if (in_array($key, $cats)) //Si cette catégorie contient notre catégorie, on l'explore
			{
				$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/minus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/opened_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '">
				<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;padding-left:30px;">'
				. show_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
				$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $key . "'", __LINE__, __FILE__);
				//Si cette catégorie contient des sous catégories, on propose de voir son contenu
				if ($sub_cats_number > 0)
					$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '"></span></li>';
				else //Sinon on n'affiche pas le "+"
					$line .= '<li style="padding-left:17px;"><img src="' . $Template->get_module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span></li>';
			}
		}
	}
	return "\n" . $line;
}

//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
function wiki_find_subcats(&$array, $id_cat)
{
	global $_WIKI_CATS;
	//On parcourt les catégories et on détermine les catégories filles
	foreach ($_WIKI_CATS as $key => $value)
	{
		if ($value['id_parent'] == $id_cat)
		{
			$array[] = $key;
			//On rappelle la fonction pour la catégorie fille
			wiki_find_subcats($array, $key);
		}
	}
}

?>