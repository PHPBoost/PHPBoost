<?php
/*##################################################
 *                              wiki_functions.php
 *                            -------------------
 *   begin                : May 6, 2007
 *   copyright          : (C) 2007 Sautel Benoit
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

if( defined('PHPBOOST') !== true)	exit;

//Interprétation du BBCode en ajoutant la balise [link]
function wiki_parse($var)
{
	$var = parse($var);
	$var = preg_replace('`\[link=([a-z0-9+#-]+)\](.+)\[/link\]`isU', '<a href="$1">$2</a>', $var);
	return $var;
}

//Retour au BBCode en tenant compte de [link]
function wiki_unparse($var)
{
	$var = preg_replace('`<a href="([a-z0-9+#-]+)">(.*)</a>`sU', "[link=$1]$2[/link]", $var);
	$var = unparse($var);
	return $var;
}

//Fonction de correction dans le cas où il n'y a pas de rewriting (balise link considére par défaut le rewriting activé)
function wiki_no_rewrite($var)
{
	global $CONFIG;
	if( $CONFIG['rewrite'] == 0 ) //Pas de rewriting	
		return preg_replace('`<a href="([a-z0-9+#-]+)">(.*)</a>`sU', '<a href="wiki.php?title=$1">$2</a>', $var);
	else
		return $var;
}

//Fonction de décomposition récursive (passage par référence pour la variable content qui passe de chaîne à tableau de chaînes (5 niveaux maximum)
function wiki_explode_menu(&$content, $level)
{
	//On éclate le tableau suivant la syntaxe nécessaire pour les paragraphes fils (motif: [\-]{2,6} texte [\-]{2,6}) (on capture les titres des paragraphes et les contenus, c'est alterné: pairs => contenus, impairs => titres)
	$content = preg_split('`[\n\r]{1}[\-]{' . ($level + 1) . '}[\s]+(.+)+[\s]+[\-]{' . ($level + 1) . '}[<]{1}`', "\n" . $content . "\n", -1, PREG_SPLIT_DELIM_CAPTURE);

	$nbr_occur = count($content); //On compte le nombre d'éléments du tableau (on n'utilse pas de foreach car on a besoin de savoir si les clés sont paires ou impaires)
	
	for( $i = 1; $i < $nbr_occur; $i++ ) //On passe tous les éléments du tableau, on commence à 1 car on sait qu'il n'y a rien d'intéressant avant
	{
		//Si c'est un nombre pair, cela signifie qu'il contient peut-être des (sous){0,4} catégories, on vérifie
		if( $i % 2 === 0 && $level <= 5 && preg_match('`[\-]{' . ($level + 1) . '}`isU', $content[$i]) )
		{
			wiki_explode_menu($content[$i], $level + 1); //On éclate la chaîne $content[$i] à un niveau intérieur
		}
	}
}

//Fonction d'affichage récursive
function wiki_display_menu($array_menu, &$menu, $level)
{
	if( !is_array($array_menu) ) //Si ce n'est pas un tableau
	{
		$menu = '';
		return 0;
	}
		
	$menu .= '<ol class=\"wiki_list_' . $level . '\">
	<li>';
	
	$nbr_occur = count($array_menu); //On compte le nombre d'éléments du tableau (on n'utilse pas de foreach car on a besoin de savoir si les clés sont paires ou impaires)
	
	for( $i = 1; $i < $nbr_occur; $i++ ) //On boucle sur le tableau
	{
		if( $i % 2 === 0 && is_array($array_menu[$i]) && $level <= 5 )//Si c'est un nombre pair, cela signifie qu'il contient peut-être des (sous){0,4} catégories
		{
			wiki_display_menu($array_menu[$i], $menu, $level + 1); //On appelle cette même fonction à un niveau de hiérarchie inférieur
		}
		elseif( $i % 2 === 1 && !empty($array_menu[$i]) ) //sinon on affiche simplement le titre du paragraphe et le lien vers l'ancre
		{
			$menu .= (($i === 1 || $i >= $nbr_occur - 1) ? '' : '</li><li>') . '<a href="#' . url_encode_rewrite($array_menu[$i]) . '">' . htmlentities($array_menu[$i]) . '</a>' . "\n"; //On affiche le lien vers l'ancre (on met rajoute une puce seulement si on n'est pas au premier ou au dernier élément de la liste)
		}
	}
	$menu .= '</li></ol>'; //On ferme les balises de la liste
}

function wiki_make_anchors($array) //Fonction qui crée les ancres
{
	return 'id=\"' . url_encode_rewrite($array[1]) . '\">';
}

//Catégories (affichage si on connait la catégorie et qu'on veut reformer l'arborescence)
function display_cat_explorer($id, &$cats, $display_select_link = 1)
{
	global $_WIKI_CATS;
		
	if( $id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des catégories afin de savoir quelle catégorie développer
		do
		{
			$cats[] = (int)$_WIKI_CATS[$id_cat]['id_parent'];
			$id_cat = (int)$_WIKI_CATS[$id_cat]['id_parent'];
		}	
		while( $id_cat > 0 );
	}
	

	//Maintenant qu'on connait l'arborescence on part du début
	$cats_list = '<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">' . show_cat_contents(0, $cats, $id, $display_select_link) . '</ul>';
	
	//On liste les catégories ouvertes pour la fonction javascript
	$opened_cats_list = '';
	foreach( $cats as $key => $value )
	{
		if( $key != 0 )
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
	foreach( $_WIKI_CATS as $key => $value )
	{
		//Si la catégorie appartient à la catégorie explorée
		if( $value['id_parent']  == $id_cat )
		{
			if( in_array($key, $cats) ) //Si cette catégorie contient notre catégorie, on l'explore
			{
				$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('wiki') . '/images/minus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('wiki') . '/images/opened_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '">
				<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;padding-left:30px;">'
				. show_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
				$sub_cats_number = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."wiki_cats WHERE id_parent = '" . $key . "'", __LINE__, __FILE__);
				//Si cette catégorie contient des sous catégories, on propose de voir son contenu
				if( $sub_cats_number > 0 )
					$line .= '<li><a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('wiki') . '/images/plus.png" alt="" id="img2_' . $key . '" style="vertical-align:middle" /></a> <a href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><img src="' . $Template->Module_data_path('wiki') . '/images/closed_cat.png" alt="" id="img_' . $key . '" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span><span id="cat_' . $key . '"></span></li>';
				else //Sinon on n'affiche pas le "+"
					$line .= '<li style="padding-left:17px;"><img src="' . $Template->Module_data_path('wiki') . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $key . '" class="' . ($key == $id ? 'wiki_selected_cat' : '') . '"><a href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a></span></li>';
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
	foreach( $_WIKI_CATS as $key => $value )
	{
		if( $value['id_parent'] == $id_cat )
		{
			$array[] = $key;
			//On rappelle la fonction pour la catégorie fille
			wiki_find_subcats($array, $key);
		}
	}
}

?>