<?php
/*##################################################
 *                               captcha.class.php
 *                            -------------------
 *   begin                : Februar, 06 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('CAPTCHA_VERY_EASY', 0);
define('CAPTCHA_EASY', 1);
define('CAPTCHA_NORMAL', 2);
define('CAPTCHA_HARD', 3);
define('CAPTCHA_VERY_HARD', 4);

class Captcha
{
	## Public Methods ##
	//Lancement du bench.
	function Captcha()
	{
		Captcha::update_instance(); //Mise à jour de l'instance.
		if( @extension_loaded('gd') )
			$this->gd_loaded = true;
	}
	
	//Chargement de la librairie GD?
	function gd_loaded()
	{
		if( $this->gd_loaded )
			return true;
		return false;
	}
	
	//Mise à jour de l'instance.	
	function update_instance()
	{
		static $instance = 0;
		
		$this->instance = ++$instance;
	}
		
	//Gère le niveau de difficulté du captcha.
	function set_difficulty($difficulty)
	{
		/*
		0: Mot du dictionnaire, fond uni, texte à l'horinzontal.
		1: Mot mélangé, fond uni.
		2: Mot mélangé + chiffres, fond brouillé.
		3: Mot mélangé + chiffres, identification par contour, faible ombrage.
		4: Mot mélangé + chiffres, identification par contour, faible ombrage.
		*/
		$this->difficulty = max(0, $difficulty);		
	}
	
	//Largeur de l'image.
	function set_instance($instance)
	{
		$this->instance = $instance;
	}
	
	//Largeur de l'image.
	function set_width($width)
	{
		$this->width = $width;
	}
	
	//Hauteur de l'image.
	function set_height($height)
	{
		$this->height = $height;
	}
	
	//Police.
	function set_font($font)
	{
		$this->font = $font;
	}
	
	//Validation du code.
	function is_valid()
	{
		global $Sql;
		
		$get_code = retrieve(POST, 'verif_code' . $this->instance, '', TSTRING_UNSECURE);
		$user_id = substr(strhash(USER_IP), 0, 13) . $this->instance;
		$code = $Sql->query("SELECT code FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
		//Suppression pour éviter une réutilisation du code frauduleuse.
		$Sql->query_inject("DELETE FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
		
		if( !empty($code) && $code == $get_code )
			return true;
		else 
			return false;
	}
	
	//Alerte javascript
	function js_require()
	{
		global $LANG;
		
		return $this->gd_loaded() ? 'if(document.getElementById(\'verif_code' . $this->instance . '\').value == "") {
			alert("' . $LANG['require_verif_code'] . '");
			return false;
		}' : '';
	}
	
	//Affichage du formulaire.
	function display_form($Template = false)
	{
		global $CONFIG;
		
		if( !is_object($Template) || strtolower(get_class($Template)) != 'template' )
			$Template = new Template('framework/captcha.tpl');
		
		if( $this->gd_loaded() )
		{		
			$Template->Assign_vars(array(
				'CAPTCHA_INSTANCE' => $this->instance,
				'CAPTCHA_DIFFICULTY' => $this->difficulty,
				'CAPTCHA_WIDTH' => $this->width,
				'CAPTCHA_HEIGHT' => $this->height,
				'CAPTCHA_FONT' => $this->font,
			));
			return $Template->parse(TEMPLATE_STRING_MODE);
		}
		return '';
	}
	
	//Affichage de l'image.
	function display()
	{
		global $LANG;
		
		$rand = rand(0,1);

		##Définition des couleurs##
		$array_color = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237), array(204, 42, 38), array(53, 144, 189), array(102, 102, 153), array(236, 230, 208), array(213, 171, 1), array(182, 0, 51), array(193, 73, 0), array(25, 119, 128), array(182, 181, 177), array(102, 133, 237));
		
		##Génération du code##
		$words = $LANG['_code_dictionnary'];
		
		switch($this->difficulty)
		{
			case 0;
				$code = $words[array_rand($words)]; //Mot aléatoire
				break;
			case 1:
				$code = str_shuffle($words[array_rand($words)]); //Mot mélangé aléatoire.
				break;
			case 2:
				$code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire
				array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237));
				break;
			case 3:
			case 4:
				$code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire.
				$array_color = array(array(224, 118, 27));
				break;
			default:
				$code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2)));
		}
		if( $this->difficulty > 0 )
		{	
			$code = substr($code, 0, 6);
			$code = str_replace(array('l', '1', 'o', '0'), array('', '', '', ''), $code);
		}
		
		##Création de l'image##
		if( !function_exists('imagecreatetruecolor') )
			$img = @imagecreate($this->width, $this->height);
		else
			$img = @imagecreatetruecolor($this->width, $this->height);

		//Choix aléatoire de couleur, et suppression du tableau pour éviter une réutilisation pour le texte.
		$bg_bis_index_color = array_rand($array_color);	
		list($r, $g, $b) = $this->_imagecolorallocatedark($array_color[$bg_bis_index_color], 150, 0.70); //Assombrissement de la couleur de fond.
		$bg_img = @imagecolorallocate($img, $r, $g, $b);
		if( $this->difficulty < 3 )
			unset($array_color[$bg_bis_index_color]);

		$bg_index_color = array_rand($array_color);	
		list($r, $g, $b) = $array_color[$bg_index_color];
		$bg = @imagecolorallocate($img, $r, $g, $b);
		if( $this->difficulty < 3)
			unset($array_color[$bg_index_color]);

		$bg_bis_index_color = array_rand($array_color);	
		list($r, $g, $b) = $array_color[$bg_bis_index_color];
		$bg_bis = @imagecolorallocate($img, $r, $g, $b);
		if( $this->difficulty < 3)
			unset($array_color[$bg_bis_index_color]);

		$black = @imagecolorallocate($img, 0, 0, 0);

		##Création de l'arrère plan
		//Couleur de fond
		@imagefilledrectangle($img, 0, 0, $this->width, $this->height, $bg_img);
		//Brouillage de l'image.
		$style = array($bg, $bg, $bg, $bg, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis);
		@imagesetstyle($img, $style);
		if( $this->difficulty > 0 )
		{
			if( $rand )
				for($i = 0; $i <= $this->height; $i = ($i + 2))
					@imageline($img, 0, $i, $this->width, $i, IMG_COLOR_STYLED);
			else
				for($i = $this->height; $i >= 0; $i = ($i - 2))
					@imageline($img, 0, $i, $this->width, $i, IMG_COLOR_STYLED);
		}
		
		##Attribut du code à écrire##	
		//Centrage du texte.	
		$global_font_size = 24;
		$array_size_ttf = @imagettfbbox($global_font_size + 2, 0, $this->font, $code);
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($this->width/2) - ($text_width/2);
		$text_y = ($this->height/2) + ($text_height/2);

		preg_match_all('/.{1}/s', $code, $matches);
		foreach($matches[0] as $key => $letter)
		{	
			//Allocation des couleurs.
			$index_color = array_rand($array_color);
			list($r, $g, $b) = $array_color[$index_color];
			$text_color = @imagecolorallocate($img, $r, $g, $b);
			list($r, $g, $b) = $this->_imagecolorallocatedark($array_color[$index_color]);
			$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			$font_size = rand($global_font_size - 4, $global_font_size);
			$angle = rand(-15, 15);
			$move_y = $text_y + rand(-15, 4);
			if( $this->difficulty < 2 )
			{	
				$angle = 0;
				$move_y = $text_y - 2;
			}
			
			//Ajout de l'ombre.
			if( $this->difficulty == 4 )
			{
				list($r, $g, $b) = $this->_imagecolorallocatedark($array_color[$index_color], 90, 0.50);
				$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			}
			@imagettftext($img, $font_size, $angle, ($text_x + 1), ($move_y + 1), $text_color_dark, $this->font, $letter);	
			
			//Ecriture du code.
			@imagettftext($img, $font_size, $angle, $text_x, $move_y, $text_color, $this->font, $letter);
			$array_size_ttf = @imagettfbbox($font_size, $angle, $this->font, $code);
			$text_width = max(abs($array_size_ttf[2] - $array_size_ttf[0]), 5);
			$text_x += $global_font_size - 6;
		}

		//Bordure
		@imagerectangle($img, 0, 0, $this->width - 1, $this->height - 1, $black);

		##Envoi de l'image##
		imagejpeg($img);
		imagedestroy($img);
		
		//Enregistrement du code pour l'utilisateur dans la base de données;
		$this->_save_user($code);
	}
	
	## Private Methods ##
	//Calcul de la version sombre de la couleur. Paramètres couleur de l'effet 3D, mask_color: 0 pour sombre, 255 pour lumineux; similar_color: entre 0.40 (très différents et 0.99 très proche.
	function _imagecolorallocatedark($array_color, $mask_color = 0, $similar_color = 0.40)
	{
		list($r, $g, $b) = $array_color;
		$rd = round($r * $similar_color) + round($mask_color * (1 - $similar_color));
		$gd = round($g * $similar_color) + round($mask_color * (1 - $similar_color));
		$bd = round($b * $similar_color) + round($mask_color * (1 - $similar_color));

		return array($rd, $gd, $bd);
	}
	
	//Enregistrement du code pour l'utilisateur dans la base de données;
	function _save_user($code)
	{
		global $Sql;
		
		$user_id = substr(strhash(USER_IP), 0, 13) . $this->instance;
		$check_user_id = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if( $check_user_id == 1 )
			$Sql->query_inject("UPDATE ".PREFIX."verif_code SET code = '" . $code . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		else
			$Sql->query_inject("INSERT INTO ".PREFIX."verif_code (user_id, code, timestamp) VALUES ('" . $user_id . "', '" . $code . "', '" . time() . "')", __LINE__, __FILE__);
	}
		
	## Private Attributes ##
	var $instance = 0; //Numéro d'instance.
	var $gd_loaded = false; //Chargement de la librairie GD.
	var $width = 160; //Largeur de l'image générée.
	var $height = 50; //Largeur de l'image générée.
	var $font = '../kernel/data/fonts/impact.ttf'; //Police
	var $difficulty = 2; //Difficulté du code. 
}

?>