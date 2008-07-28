<?php
/*##################################################
*                             parser.class.php
*                            -------------------
*   begin                : November 29, 2007
*   copyright          : (C) 2007 Régis Viarre, Benoit Sautel, Loïc Rouchon
*   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, horn@phpboost.com
*
*   
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
* 
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

//Constantes utilisées
define('DO_NOT_ADD_SLASHES', false);
define('PICK_UP', true);
define('REIMPLANT', false);

//Classe de gestion du contenu
class ContentParser
{
	######## Public #######
	//Constructeur
	function ContentParser()
	{
		global $CONFIG;
		$this->html_auth =& $CONFIG['html_auth'];
		$this->forbidden_tags =& $CONFIG['forbidden_tags'];
		$this->content = '';
		$this->parsed_content = '';
	}

	//Fonction qui renvoie le contenu traité
	function get_content($addslashes = true)
	{
		if( $addslashes )
			return addslashes(trim($this->content));
		else
			return trim($this->content);
	}
	
	//Fonction de chargement de texte
	function set_content($content)
	{
		$this->content = trim(!MAGIC_QUOTES ? $content : stripslashes($content));
	}
	
	function set_html_auth($array_auth)
	{
		global $CONFIG;
		if( is_array($array_auth) )
			$this->auth = $array_auth;
		else
			$this->auth =& $CONFIG['html_auth'];
	}
	
	function get_parsed_content($addslashes = true)
	{
		if( $addslashes )
			return addslashes(trim($this->parsed_content));
		else
			return trim($this->parsed_content);
	}
	
	function set_forbidden_tags($forbidden_tags)
	{
		if( is_array($forbidden_tags) )
			$this->forbidden_tags = $forbidden_tags;
	}
	
	function get_forbidden_tags()
	{
		return $this->forbidden_tags;
	}
	
	//These methods should be abstract	
	/*abstract*/ function parse($forbidden_tags = array(), $html_protect = true) {}
	/*abstract*/ function unparse() {}
	
	//Parse temps réel => détection des balisses [code]  et remplacement, coloration si contient du code php.
	//This function exists whatever type of content you have because it's use to finish parsing of a recorded string
	function second_parse()
	{
		global $LANG;
		
		$this->parsed_content = $this->content;
		
		//Balise code
		if( strpos($this->parsed_content, '[[CODE') !== false )
			$this->parsed_content = preg_replace_callback('`\[\[CODE(?:=([a-z0-9-]+))?(?:,(0|1)(,0|1)?)?\]\](.+)\[\[/CODE\]\]`sU', array(&$this, '_callback_highlight_code'), $this->parsed_content);
		
		//Balise latex.
		if( strpos($this->parsed_content, '[[MATH]]') !== false )
			$this->parsed_content = preg_replace_callback('`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU', array(&$this, '_math_code'), $this->parsed_content);
	}
	
	####### Protected #######
	//This array should be static
	var $tag = array('b', 'i', 'u', 's', 'title', 'stitle', 'style', 'url', 
	'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
	'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym'); //Balises supportées.
	var $content = '';
	var $array_tags = array();
	var $parsed_content;
	var $html_auth = array();
	var $forbidden_tags = array();
	
	
	//Fonction pour éclater la chaîne selon les tableaux (gestion de l'imbrication infinie)
	function _split_imbricated_tag(&$content, $tag, $attributes)
	{
		$content = $this->_preg_split_safe_recurse($content, $tag, $attributes);
		//1 élément représente les inter tag, un les attributs tag et l'autre le contenu
		$nbr_occur = count($content);
		for($i = 0; $i < $nbr_occur; $i++)
		{
			//C'est le contenu d'un tag, il contient un sous tag donc on éclate
			if( ($i % 3) === 2 && preg_match('`\[' . $tag . '(?:' . $attributes . ')?\].+\[/' . $tag . '\]`s', $content[$i]) ) 
				$this->_split_imbricated_tag($content[$i], $tag, $attributes);
		}
	}
	
	//Fonction d'éclatement de chaîne supportant l'imbrication de tags
	function _preg_split_safe_recurse($content, $tag, $attributes)
	{
   		// Définitions des index de position de début des tags valides
		$_index_tags = $this->_index_tags($content, $tag, $attributes);
		$size = count($_index_tags);
		$parsed = array();
 
   		// Stockage de la chaîne avant le premier tag dans le cas ou il y a au moins une balise ouvrante
		if( $size >= 1 )
			array_push($parsed, substr($content, 0, $_index_tags[0]));
		else
			array_push($parsed, $content);
 	
		for ($i = 0; $i < $size; $i++)
		{
			$current_index = $_index_tags[$i];
			// Calcul de la sous-chaîne pour l'expression régulière
			if( $i == ($size - 1) )
				$sub_str = substr($content, $current_index); 
			else
				$sub_str = substr($content, $current_index, $_index_tags[$i + 1] - $current_index);
	
			// Mise en place de l'éclatement de la sous-chaine
			$mask = '`\[' . $tag . '(' . $attributes . ')?\](.+)\[/' . $tag . '\](.+)?`s';
			$local_parsed = preg_split($mask, $sub_str, -1, PREG_SPLIT_DELIM_CAPTURE);
	
			if ( count($local_parsed) == 1 )
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
			if( $i < ($size - 1) )
			{
				// On prend la chaine après le tag de fermeture courant jusqu'au prochain tag d'ouverture
				$current_tag_len = strlen('[' . $tag . $local_parsed[1] . ']' . $local_parsed[2] . '[/' . $tag . ']');
				$end_pos = $_index_tags[$i + 1] - ($current_index + $current_tag_len);
				array_push($parsed, substr($local_parsed[3], 0, $end_pos ));
			}
			elseif( isset($local_parsed[3]) ) // c'est la fin, il n'y a pas d'autre tag ouvrant après
				array_push($parsed, $local_parsed[3]);
		}
		return $parsed;
	}
	
	//Fonction de détection du positionnement des balises imbriquées
	function _index_tags($content, $tag, $attributes)
	{
		$pos = -1;
		$nb_open_tags = 0;
		$tag_pos = array();
 
		while( ($pos = strpos($content, '[' . $tag, $pos + 1)) !== false )
		{
			// nombre de tag de fermeture déjà rencontré
			$nb_close_tags = substr_count(substr($content, 0, ($pos + strlen('['.$tag))), '[/'.$tag.']');
 
			// Si on trouve un tag d'ouverture, on sauvegarde sa position uniquement si il y a autant + 1 de tags fermés avant et on itère sur le suivant
			if( $nb_open_tags == $nb_close_tags )
			{
				$open_tag = substr($content, $pos, (strpos($content, ']', $pos + 1) + 1 - $pos));
				$match = preg_match('`\[' . $tag . '(' . $attributes . ')?\]`', $open_tag);
				if( $match == 1 )
					$tag_pos[count($tag_pos)] = $pos; 
			}
			$nb_open_tags++;
		}
		return $tag_pos;
	}
	
	//Remplacement recursif des balises imbriquées.
	function _parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = substr_count($this->parsed_content, $match);
		for($i = 0; $i <= $nbr_match; $i++)
			$this->parsed_content = preg_replace($regex, $replace, $this->parsed_content); 
	}
	
	//Fonction qui retire les portions de code pour ne pas y toucher (elle les stocke dans un array)
	function _pick_up_tag($tag, $arguments = '')
	{
		//On éclate le contenu selon les tags (avec imbrication bien qu'on ne les gèrera pas => ça permettra de faire [code][code]du code[/code][/code])
		$split_code = $this->_preg_split_safe_recurse($this->parsed_content, $tag, $arguments);
		
		$num_codes = count($split_code);
		//Si on a des apparitions de la balise
		if( $num_codes > 1 )
		{
			$this->parsed_content = '';
			$id_code = 0;
			//On balaye le tableau trouvé
			for( $i = 0; $i < $num_codes; $i++ )
			{
				//Contenu inter tags
				if( $i % 3 == 0 )
				{
					$this->parsed_content .= $split_code[$i];
					//Si on n'est pas après la dernière balise fermante, on met une balise de signalement de la position du tag
					if( $i < $num_codes - 1 )
						$this->parsed_content .= '[' . strtoupper($tag) . '_TAG_' . $id_code++ . ']';
				}
				//Contenu des balises
				elseif( $i % 3 == 2 )
					//Enregistrement dans le tableau du contenu des tags à isoler
					$this->array_tags[$tag][] = '[' . $tag . $split_code[$i - 1] . ']' . $split_code[$i] . '[/' . $tag . ']';
			}
		}
	}
		
	//Fonction qui réimplante les portions de code
	function _reimplant_tag($tag)
	{
		//Si cette balise a  été isolée
		if( !array_key_exists($tag, $this->array_tags) )
			return false;
		
		$num_code = count($this->array_tags[$tag]);
		
		//On réinjecte tous les contenus des balises
		for( $i = 0; $i < $num_code; $i++ )
			$this->parsed_content = str_replace('[' . strtoupper($tag) . '_TAG_' . $i . ']', $this->array_tags[$tag][$i], $this->parsed_content);
		
		//On efface tout ce qu'on a prélevé du array
		$this->array_tags[$tag] = array();
		
		return true;
	}
	
	//Fonction de retour pour les tableaux
	function _unparse_table()
	{
		//On boucle pour parcourir toutes les imbrications
		while( strpos($this->parsed_content, '<table') !== false )
			$this->parsed_content = preg_replace('`<table class="bb_table"([^>]*)>(.*)</table>`sU', '[table$1]$2[/table]', $this->parsed_content);
		while( strpos($this->parsed_content, '<tr') !== false )
			$this->parsed_content = preg_replace('`<tr class="bb_table_row">(.*)</tr>`sU', '[row]$1[/row]', $this->parsed_content);
		while( strpos($this->parsed_content, '<th') !== false )
			$this->parsed_content = preg_replace('`<th class="bb_table_head"([^>]*)>(.*)</th>`sU', '[head$1]$2[/head]', $this->parsed_content);
		while( strpos($this->parsed_content, '<td') !== false )
			$this->parsed_content = preg_replace('`<td class="bb_table_col"([^>]*)>(.*)</td>`sU', '[col$1]$2[/col]', $this->parsed_content);
	}

	//Fonction de retour pour les listes
	function _unparse_list()
	{
		//On boucle tant qu'il y a de l'imbrication
		while( strpos($this->parsed_content, '<ul class="bb_ul">') !== false )
			$this->parsed_content = preg_replace('`<ul( style="[^"]+")? class="bb_ul">(.+)</ul>`sU', '[list$1]$2[/list]', $this->parsed_content);
		while( strpos($this->parsed_content, '<ol class="bb_ol">') !== false )
			$this->parsed_content = preg_replace('`<ol( style="[^"]+")? class="bb_ol">(.+)</ol>`sU', '[list=ordered$1]$2[/list]', $this->parsed_content);
		while( strpos($this->parsed_content, '<li class="bb_li">') !== false )
			$this->parsed_content = preg_replace('`<li class="bb_li">(.+)</li>`isU', '[*]$1', $this->parsed_content);
	}
	
	//Fonction de retour pour le html (prélèvement ou réinsertion)
	function _unparse_html($action)
	{
		//Prélèvement du HTML
		if( $action == PICK_UP )
		{
			$mask = '`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`is';
			$content_split = preg_split($mask, $this->parsed_content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if( $content_length > 1 )
			{
				$this->parsed_content = '';
				for($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if( $i % 2 == 0 )
					{
						$this->parsed_content .= $content_split[$i];
						//Ajout du tag de remplacement
						if( $i < $content_length - 1 )
							$this->parsed_content .= '[HTML_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					else
					{
						$this->array_tags['html_unparse'][] = $content_split[$i];
					}
				}
				
				//On protège le code HTML à l'affichage qui vient non protégé de la base de données
				$this->array_tags['html_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['html_unparse']);
			}
			return true;
		}
		//Réinsertion du HTML
		else
		{
			if( !array_key_exists('html_unparse', $this->array_tags) )
				return false;
				
			$content_length = count($this->array_tags['html_unparse']);

			if( $content_length > 0 )
			{
				for( $i = 0; $i < $content_length; $i++ )
					$this->parsed_content = str_replace('[HTML_UNPARSE_TAG_' . $i . ']', '[html]' . $this->array_tags['html_unparse'][$i] . '[/html]', $this->parsed_content);
				$this->array_tags['html_unparse'] = array();
			}
			return true;
		}
	}
	
	//Fonction de retour pour le html (prélèvement ou réinsertion)
	function _unparse_code($action)
	{
		//Prélèvement du HTML
		if( $action == PICK_UP )
		{
			$mask = '`\[\[CODE(=[a-z0-9-]+(?:,(?:0|1)(?:,1)?)?)?\]\]' . '(.+)' . '\[\[/CODE\]\]`sU';
			$content_split = preg_split($mask, $this->parsed_content, -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;
			
			if( $content_length > 1 )
			{
				$this->parsed_content = '';
				for($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if( $i % 3 == 0 )
					{
						$this->parsed_content .= $content_split[$i];
						//Ajout du tag de remplacement
						if( $i < $content_length - 1 )
							$this->parsed_content .= '[CODE_UNPARSE_TAG_' . $id_tag++ . ']';
					}
					elseif( $i % 3 == 2 )
					{
						$this->array_tags['code_unparse'][] = '[code' . $content_split[$i - 1] . ']' . $content_split[$i] . '[/code]';
					}
				}
				//On protège le code HTML à l'affichage qui vient non protégé de la base de données
				$this->array_tags['code_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['code_unparse']);
			}
			return true;
		}
		//Réinsertion du HTML
		else
		{
			if( !array_key_exists('code_unparse', $this->array_tags) )
				return false;
				
			$content_length = count($this->array_tags['code_unparse']);

			if( $content_length > 0 )
			{
				for( $i = 0; $i < $content_length; $i++ )
					$this->parsed_content = str_replace('[CODE_UNPARSE_TAG_' . $i . ']', $this->array_tags['code_unparse'][$i], $this->parsed_content);
				$this->array_tags['code_unparse'] = array();
			}
			return true;
		}
	}
	
	//Coloration syntaxique suivant le langage, tracé des lignes si demandé.
	function _highlight_code($contents, $language, $line_number) 
	{
		if( $language != '' )
		{
			include_once(PATH_TO_ROOT . '/kernel/framework/content/geshi/geshi.php');
			$Geshi =& new GeSHi($contents, $language);
			
			if( $line_number ) //Affichage des numéros de lignes.
				$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

			$contents = $Geshi->parse_code();
		}
		else
		{
			$highlight = highlight_string($contents, true);
			$font_replace = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $highlight);
			$contents = preg_replace('`color="(.*?)"`', 'style="color:$1"', $font_replace);
		}
		
		return $contents ;
	} 

	//Fonction appliquée aux balises [code] temps réel.
	function _callback_highlight_code($matches)
	{
		global $LANG;
		
		$line_number = !empty($matches[2]);
		$display_info_code = empty($matches[3]);

		$contents = $this->_highlight_code($matches[4], $matches[1], $line_number);

		if( $display_info_code && !empty($matches[1]) )
			$contents = '<span class="text_code">' . sprintf($LANG['code_langage'], strtoupper($matches[1])) . '</span><div class="code">' . $contents .'</div>';
		else
			$contents = '<span class="text_code">' . $LANG['code_tag'] . '</span><div class="code" style="margin-top:3px;">'. $contents .'</div>';
			
		return $contents;
	}

	//Fonction appliquée aux balises [math] temps réel, formules matématiques.
	function _math_code($matches)
	{
		$matches[1] = str_replace('<br />', '', $matches[1]);
		$matches = mathfilter(html_entity_decode($matches[1]), 12);

		return $matches;
	}
}

?>