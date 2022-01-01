<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 01
 * @since       PHPBoost 1.6 - 2006 05 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

if (defined('PHPBOOST') !== true)	exit;

define('WIKI_MENU_MAX_DEPTH', 6);

//Parsing en ajoutant la balise [link]
function wiki_parse($contents)
{
	$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
	$parser = $content_manager->get_parser();

	//Parse la balise link
	$parser->add_module_special_tag('`\[link=([a-z0-9+#-_]+)\](.+)\[/link\]`isuU', '<a href="/wiki/$1">$2</a>');
	$parser->set_content($contents);
	$parser->parse();

	return $parser->get_content();
}

//Unparsing en tenant compte de [link]
function wiki_unparse($contents)
{
	$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
	$unparser = $content_manager->get_unparser();

	//Unparse la balise link
	$unparser->add_module_special_tag('`<a href="/wiki/([a-z0-9+#-_]+)">(.*)</a>`suU', '[link=$1]$2[/link]');
	$unparser->set_content($contents);
	$unparser->parse();

	return $unparser->get_content();
}

//Second parse -> à l'affichage
function wiki_second_parse($contents)
{
	$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
	$second_parser = $content_manager->get_second_parser();
	$second_parser->set_content(wiki_unparse($contents));
	$second_parser->parse();

	return $second_parser->get_content();
}

//Fonction de correction dans le cas où il n'y a pas de rewriting (balise link considére par défaut le rewriting activé)
function wiki_no_rewrite($var)
{
	if (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled()) //Pas de rewriting
		return preg_replace('`<a href="/wiki/([a-z0-9+#-]+)">(.*)</a>`suU', '<a href="/wiki/wiki.php?title=$1">$2</a>', $var);
	else
		return $var;
}

function remove_chapter_number_in_rewrited_title($title)
{
	return Url::encode_rewrite(preg_replace('`((?:[0-9 ]+)|(?:[IVXCL ]+))[\.-](.*)`iuU', '$2', $title));
}

//Fonction de décomposition récursive (passage par référence pour la variable content qui passe de chaîne à tableau de chaînes (5 niveaux maximum)
function wiki_explode_menu(&$content)
{
	//On supprime d'abord toutes les occurences de balises CODE que nous réinjecterons à la fin pour ne pas y toucher
	$pickup_code = wiki_pick_up_tag($content, 'code', '=[^,\s]+(?:,[01]){0,2}');
	$content = $pickup_code['contents'];

	$lines = explode("\n", $content);
	$num_lines = count($lines);
	$max_level_expected = 2;

	$list = array();

	//We read the text line by line
	foreach ($lines as $id => &$line)
	{
		for ($level = 2; $level <= $max_level_expected; $level++)
		{
			$matches = array();

			//If the line contains a title
			if (preg_match('`^(?:<br />)?\s*[\-]{' . $level . '}[\s]+(.+)[\s]+[\-]{' . $level . '}(?:<br />)?\s*$`u', $line, $matches))
			{
				$title_name = strip_tags(TextHelper::html_entity_decode($matches[1]));

				//We add it to the list
				$list[] = array($level - 1, $title_name);
				//Now we wait one of its children or its brother
				$max_level_expected = min($level + 1, WIKI_MENU_MAX_DEPTH + 1);

				//Réinsertion
				$line = '<h' . $level . ' class="formatter-title wiki-paragraph-' .  $level . '" id="paragraph-' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) .'</h' . $level . '><br />' . "\n";
			}
		}
	}

	$content = implode("\n", $lines);

	//On réinsère les fragments de code qui ont été prévelevés pour ne pas les considérer
	if (!empty($pickup_code['array_tags']['code']))
	{
		$pickup_code['array_tags']['code'] = array_map(function($string) {return preg_replace('`^\[code(=.+)?\](.+)\[/code\]$`isuU', '[[CODE$1]]$2[[/CODE]]', TextHelper::htmlspecialchars($string, ENT_NOQUOTES));}, $pickup_code['array_tags']['code']);
		$content = wiki_reimplant_tag('code', $pickup_code['array_tags'], $content);
	}

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
		$title_link = '<a href="#paragraph-' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) . '</a>';

		if ($current_level > $last_level)
		{
			$menu .= '<ol class="wiki-list wiki-list-' . $current_level . '"><li>' . $title_link;
		}
		elseif ($current_level == $last_level)
		{
			$menu .= '</li><li>' . $title_link;
		}
		else
		{
			if (TextHelper::substr($menu, TextHelper::strlen($menu) - 4, 4) == '<li>')
			{
				$menu = TextHelper::substr($menu, 0, TextHelper::strlen($menu) - 4);
			}
			$menu .= str_repeat('</li></ol>', $last_level - $current_level) . '</li><li>' . $title_link;
		}
		$last_level = $title[0];
	}

	//End
	if (TextHelper::substr($menu, TextHelper::strlen($menu) - 4, 4) == '<li>')
	{
		$menu = TextHelper::substr($menu, 0, TextHelper::strlen($menu) - 4);
	}
	$menu .= str_repeat('</li></ol>', $last_level);

	return $menu;
}

//Catégories (affichage si on connait la catégorie et qu'on veut reformer l'arborescence)
function display_wiki_cat_explorer($id, &$cats, $display_select_link = 1)
{
	$categories = WikiCategoriesCache::load()->get_categories();

	if ($id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des catégories afin de savoir quelle catégorie développer
		do
		{
			$cats[] = (int)$categories[$id_cat]['id_parent'];
			$id_cat = (int)$categories[$id_cat]['id_parent'];
		}
		while ($id_cat > 0);
	}


	//Maintenant qu'on connait l'arborescence on part du début
	$cats_list = '<div class="no-list"><ul>' . show_wiki_cat_contents(0, $cats, $id, $display_select_link) . '</ul></div>';

	//On liste les catégories ouvertes pour la fonction javascript
	$opened_cats_list = '';
	foreach ($cats as $key => $value)
	{
		if ($key != 0)
			$opened_cats_list .= 'cat_status[' . $key . '] = 1;' . "\n";
	}
	return '<script>
	<!--
' . $opened_cats_list . '
	-->
	</script>
	' . $cats_list;
}

//Fonction récursive pour l'affichage des catégories
function show_wiki_cat_contents($id_cat, $cats, $id, $display_select_link)
{
	$line = '';
	foreach (WikiCategoriesCache::load()->get_categories() as $key => $cat)
	{
		//Si la catégorie appartient à la catégorie explorée
		if ($cat['id_parent']  == $id_cat)
		{
			if (in_array($key, $cats)) //Si cette catégorie contient notre catégorie, on l'explore
			{
				$line .= '<li class="sub-cat-tree"><a class="parent" href="javascript:show_wiki_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="far fa-minus-square" id="img-subfolder-' . $key . '"></i><i class="fa fa-folder-open" id="img-folder-' . $key . '"></i></a> <a id="class-' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . stripslashes($cat['title']) . '</a><span id="cat-' . $key . '">
				<ul>'
				. show_wiki_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				//On compte le nombre de catégories présentes pour savoir si on donne la possibilité de faire un sous dossier
				$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "wiki_cats", 'WHERE id_parent = :id', array('id' => $key));
				//Si cette catégorie contient des sous catégories, on propose de voir son contenu
				if ($sub_cats_number > 0)
					$line .= '<li class="sub-cat-tree"><a class="parent" href="javascript:show_wiki_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="far fa-plus-square" id="img-subfolder-' . $key . '"></i> <i class="fa fa-folder" id="img-folder-' . $key . '"></i></a> <a id="class-' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . stripslashes($cat['title']) . '</a><span id="cat-' . $key . '"></span></li>';
				else //Sinon on n'affiche pas le "+"
					$line .= '<li class="sub-cat-tree"><a id="class-' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');"><i class="fa fa-folder"></i> ' . stripslashes($cat['title']) . '</a></li>';
			}
		}
	}
	return "\n" . $line;
}

//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
function wiki_find_subcats(&$array, $id_cat)
{
	//On parcourt les catégories et on détermine les catégories filles
	foreach (WikiCategoriesCache::load()->get_categories() as $key => $value)
	{
		if ($value['id_parent'] == $id_cat)
		{
			$array[] = $key;
			//On rappelle la fonction pour la catégorie fille
			wiki_find_subcats($array, $key);
		}
	}
}

function build_wiki_cat_children($cats_tree, $cats, $id_parent = 0)
{
	if (!empty($cats))
	{
		$i = 0;
		$nb_cats = count($cats);
		$children = array();
		while ($i <= $nb_cats)
		{
			if (isset($cats[$i]) && $cats[$i]['id_parent'] == $id_parent)
			{
				$id = $cats[$i]['id'];
				$feeds_cat = new FeedsCat('wiki', $id, stripslashes($cats[$i]['title']));

				// Decrease the complexity
				unset($cats[$i]);
				$cats = array_merge($cats); // re-index the array
				$nb_cats = count($cats);

				build_wiki_cat_children($feeds_cat, $cats, $id);
				$cats_tree->add_child($feeds_cat);
			}
			else
			{
				$i++;
			}
		}
	}
}

/**
 * @desc Splits a string according to a regular expression. The matched pattern can be nested and must follow the BBCode syntax,
 * i.e matching [tag=args]content of the tag[/tag].
 * It returns an array
 * For example, il you have this: $my_str = '[tag=1]test1[/tag]test2[tag=2]test3[tag=3]test4[/tag]test5[/tag]?est6';
 * You call it like that: ContentFormattingParser::preg_split_safe_recurse($my_str, 'tag', '[0-9]');
 * It will return you array('', '1', 'test1', 'test2', '2', array('test3', '3', 'test4', 'test5'), 'test6').
 * @param $content string Content into which you want to search the pattern
 * @param $tag string BBCode tage name
 * @param $attributes string The regular expression (PCRE syntax) corresponding to the arguments which you want to match.
 * There mustn't be any matching parenthesis into that regular expression
 * @return string[] the split string
 */
function wiki_preg_split_safe_recurse($content, $tag, $attributes)
{
	// Définitions des index de position de début des tags valides
	$index_tags = wiki_index_tags($content, $tag, $attributes);
	$size = count($index_tags);
	$parsed = array();

	// Stockage de la chaîne avant le premier tag dans le cas ou il y a au moins une balise ouvrante
	if ($size >= 1)
	{
		array_push($parsed, TextHelper::substr($content, 0, $index_tags[0]));
	}
	else
	{
		array_push($parsed, $content);
	}

	for ($i = 0; $i < $size; $i++)
	{
		$current_index = $index_tags[$i];
		// Calcul de la sous-chaîne pour l'expression régulière
		if ($i == ($size - 1))
		{
			$sub_str = TextHelper::substr($content, $current_index);
		}
		else
		{
			$sub_str = TextHelper::substr($content, $current_index, $index_tags[$i + 1] - $current_index);
		}

		// Mise en place de l'éclatement de la sous-chaine
		$mask = '`\[\[' . TextHelper::strtoupper($tag) . '(' . $attributes . ')?\]\](.*)\[\[/' . TextHelper::strtoupper($tag) . '\]\](.+)?`siu';
		$local_parsed = preg_split($mask, $sub_str, -1, PREG_SPLIT_DELIM_CAPTURE);

		if (count($local_parsed) == 1)
		{
			// Remplissage des résultats
			$parsed[count($parsed) - 1] .= $local_parsed[0]; // Ce n'est pas un tag
		}
		else
		{
			// Remplissage des résultats
			array_push($parsed, $local_parsed[1]);  // attributs du tag
			array_push($parsed, $local_parsed[2]);  // contenu du tag
		}

		// Chaine après le tag
		if ($i < ($size - 1))
		{
			// On prend la chaine après le tag de fermeture courant jusqu'au prochain tag d'ouverture
			$current_tag_len = TextHelper::strlen('[[' . $tag . $local_parsed[1] . ']]' . $local_parsed[2] . '[[/' . $tag . ']]');
			$end_pos = $index_tags[$i + 1] - ($current_index + $current_tag_len);
			array_push($parsed, TextHelper::substr($local_parsed[3], 0, $end_pos ));
		}
		elseif (isset($local_parsed[3]))
		{   // c'est la fin, il n'y a pas d'autre tag ouvrant après
			array_push($parsed, $local_parsed[3]);
		}
	}
	return $parsed;
}

/**
 * @desc Indexes the position of all the tags in the document. Returns the list of the positions of each tag.
 * @param $content string Content into which index the positions.
 * @param $tag string tag name
 * @param $attributes The regular expression matching the parameters of the tag (see the preg_split_safe_recurse method).
 * @return int[] The positions of the opening tags.
 */
function wiki_index_tags($content, $tag, $attributes)
{
	$pos = -1;
	$nb_open_tags = 0;
	$tag_pos = array();

	while (($pos = strpos($content, '[[' . TextHelper::strtoupper($tag), $pos + 1)) !== false)
	{
		// nombre de tags de fermeture déjà rencontrés
		$nb_close_tags = TextHelper::substr_count(TextHelper::substr($content, 0, ($pos + TextHelper::strlen('[['. TextHelper::strtoupper($tag)))), '[[/'. TextHelper::strtoupper($tag) .']]');

		// Si on trouve un tag d'ouverture, on sauvegarde sa position uniquement si il y a autant + 1 de tags fermés avant et on itère sur le suivant
		if ($nb_open_tags == $nb_close_tags)
		{
			$open_tag = TextHelper::substr($content, $pos, (strpos($content, ']]', $pos + 2) + 2 - $pos));
			$match = preg_match('`\[\[' . $tag . '(' . $attributes . ')?\]\]`ui', $open_tag);
			if ($match == 1)
			{
				$tag_pos[count($tag_pos)] = $pos;
			}
		}
		$nb_open_tags++;
	}
	return $tag_pos;
}

/**
 * @desc Removes the content of the tag $tag and replaces them by an identifying code. They will be reinserted in the content by the reimplant_tags method.
 * It enables you to treat the whole string enough affecting the interior of some tags.
 * Example: $my_parser contains this content: 'test1[tag=1]test2[/tag]test3'
 * $my_parser->pick_up_tag('tag', '[0-9]'); will replace the content of the parser by 'test1[CODE_TAG_1]test3'
 * @param $content string The content
 * @param $tag string The tag to isolate
 * @param $arguments string The regular expression matching the arguments syntax.
 */
function wiki_pick_up_tag($content, $tag, $arguments = '')
{
	$parsed_content = '';
	$array_tags = array();

	//On éclate le contenu selon les tags (avec imbrication bien qu'on ne les gèrera pas => ça permettra de faire [code][code]du code[/code][/code])
	$split_code = wiki_preg_split_safe_recurse($content, $tag, $arguments);

	$num_codes = count($split_code);
	//Si on a des apparitions de la balise
	if ($num_codes > 1)
	{
		$id_code = 0;
		//On balaye le tableau trouvé
		for ($i = 0; $i < $num_codes; $i++)
		{
			//Contenu inter tags
			if ($i % 3 == 0)
			{
				$parsed_content .= $split_code[$i];
				//Si on n'est pas après la dernière balise fermante, on met une balise de signalement de la position du tag
				if ($i < $num_codes - 1)
				{
					$parsed_content .= '[' . TextHelper::strtoupper($tag) . '_TAG_' . $id_code++ . ']';
				}
			}
			//Contenu des balises
			elseif ($i % 3 == 2)
			{
				//Enregistrement dans le tableau du contenu des tags à isoler
				$array_tags[$tag][] = '[' . $tag . $split_code[$i - 1] . ']' . str_replace('<br />', "\n", $split_code[$i]) . '[/' . $tag . ']';
			}
		}
	}
	else
		$parsed_content = $content;

	return array('contents' => $parsed_content, 'array_tags' => $array_tags);
}

/**
 * @desc reimplants the code which has been picked up by the _pick_up method.
 * @param $tag string tag to reimplant.
 * @param mixed[] $array_tags array of tags that were pickup to reimplant.
 * @param $content string the content
 * @return bool True if the reimplantation succed, otherwise false.
 */
function wiki_reimplant_tag($tag, $array_tags, $content)
{
	//Si cette balise a  été isolée
	if (!array_key_exists($tag, $array_tags))
	{
		return false;
	}

	$num_code = count($array_tags[$tag]);

	//On réinjecte tous les contenus des balises
	for ($i = 0; $i < $num_code; $i++)
	{
		$content = str_replace('[' . TextHelper::strtoupper($tag) . '_TAG_' . $i . ']', $array_tags[$tag][$i], $content);
	}

	//On efface tout ce qu'on a prélevé du array
	$array_tags[$tag] = array();

	return $content;
}
?>
