<?php
/*##################################################
 *                               captcha.class.php
 *                            -------------------
 *   begin                : Februar, 06 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class provide you an easy way to prevent spam by bot in public formular.
 * @package {@package}
 */
class Captcha
{
	const CAPTCHA_VERY_EASY = 0;
	const CAPTCHA_EASY = 1;
	const CAPTCHA_NORMAL = 2;
	const CAPTCHA_HARD = 3;
	const CAPTCHA_VERY_HARD = 4;

	private $instance = 0; //Instance identifier
	private $gd_loaded = false; //Is Gd library is loaded
	private $width = 160; //Image width
	private $height = 50; //Image height
	private $code = ''; //Captcha code
	private $font = PATH_TO_ROOT .'/kernel/data/fonts/impact.ttf'; //Font
	private $difficulty = self::CAPTCHA_NORMAL; //Difficulty to guess the image
	private $html_id = '';
	private $user_id; //User identifier in database

	private static $instance_number = 0;

	/**
	 * @desc Captcha constructor. It allows you to create multiple instance of captcha, and check if GD is loaded.
	 */
	public function __construct()
	{
		$this->update_instance(); //Mise à jour de l'instance.
		if (@extension_loaded('gd')) //TODO à remplacer par les fonctions verifs du kernel
		{
			$this->gd_loaded = true;
		}
		$this->html_id = 'verif_code' . $this->instance;
		$this->user_id = $this->get_user_id();
	}

	/**
	 * @desc Checks if captcha is loaded, disabled for members.
	 * @return boolean true if is loaded, false otherwise
	 */
	public function is_available()
	{
		if ($this->gd_loaded)
		{
			return true;
		}
		return false;
	}

	/**
	 * @desc Check if the code is valid, then delete it in the database to avoid multiple attempts.
	 * @return boolean true if is valid, false otherwise.
	 */
	public function is_valid()
	{
		global $Sql, $User;

		if (!$this->is_available() || $User->check_level(MEMBER_LEVEL)) //Non activé, retourne vrai.
		{
			return true;
		}

		$get_code = retrieve(POST, $this->html_id, '', TSTRING_UNCHANGE);
		$captcha = $Sql->query_array(DB_TABLE_VERIF_CODE, 'code', 'difficulty', "WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);

		//Suppression pour éviter une réutilisation du code frauduleuse.
		$Sql->query_inject("DELETE FROM " . DB_TABLE_VERIF_CODE . " WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);

		if (!empty($captcha['code']) && $captcha['code'] == $get_code && $captcha['difficulty'] == $this->difficulty)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @desc Javascript alert if the formular of the captcha code is empty.
	 */
	public function js_require()
	{
		return $this->is_available() ? 'if (document.getElementById(\'verif_code' . $this->instance . '\').value == "") {
			alert("' . LangLoader::get_message('require_verif_code', 'main') . '");
			return false;
		}' : '';
	}

	/**
	 * @desc Display captcha formular.
	 * @param object $Template (optional) The template used to create and display the captcha formular.
	 * @return string The parsed template.
	 */
	public function display_form($Template = false)
	{
		$this->save_user();

		if (!is_object($Template) || !($Template instanceof Template))
		{
			$Template = new FileTemplate('framework/captcha.tpl');
		}

		if ($this->is_available())
		{
			$Template->put_all(array(
				'CAPTCHA_INSTANCE' => $this->instance,
				'CAPTCHA_WIDTH' => $this->width,
				'CAPTCHA_HEIGHT' => $this->height,
				'CAPTCHA_FONT' => $this->font,
				'CAPTCHA_DIFFICULTY' => $this->difficulty
			));
			return $Template->render();
		}
		return '';
	}

	/**
	 * @desc Display the captcha image to the user, and set the code to decrypt in the database.
	 */
	public function display()
	{
		$this->generate_code(); //Mise à jour du code.

		$rand = rand(0,1);

		## Définition des couleurs selon la difficulté ##
		switch ($this->difficulty)
		{
			case 2:
				$array_color = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237));
				break;
			case 3:
			case 4:
				$array_color = array(array(224, 118, 27));
				break;
			default :
		        $array_color = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237), array(204, 42, 38), array(53, 144, 189), array(102, 102, 153), array(236, 230, 208), array(213, 171, 1), array(182, 0, 51), array(193, 73, 0), array(25, 119, 128), array(182, 181, 177), array(102, 133, 237));
		}

		//Enregistrement du code pour l'utilisateur dans la base de données;
		$this->update_code();

		## Création de l'image ##
		if (!function_exists('imagecreatetruecolor'))
		{
			$img = @imagecreate($this->width, $this->height);
		}
		else
		{
			$img = @imagecreatetruecolor($this->width, $this->height);
		}

		//Choix aléatoire de couleur, et suppression du tableau pour éviter une réutilisation pour le texte.
		$bg_bis_index_color = array_rand($array_color);
		list($r, $g, $b) = Captcha::image_color_allocate_dark($array_color[$bg_bis_index_color], 150, 0.70); //Assombrissement de la couleur de fond.
		$bg_img = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
		{
			unset($array_color[$bg_bis_index_color]);
		}

		$bg_index_color = array_rand($array_color);
		list($r, $g, $b) = $array_color[$bg_index_color];
		$bg = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
		{
			unset($array_color[$bg_index_color]);
		}

		$bg_bis_index_color = array_rand($array_color);
		list($r, $g, $b) = $array_color[$bg_bis_index_color];
		$bg_bis = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
		{
			unset($array_color[$bg_bis_index_color]);
		}

		$black = @imagecolorallocate($img, 0, 0, 0);

		##Création de l'arrère plan
		//Couleur de fond
		@imagefilledrectangle($img, 0, 0, $this->width, $this->height, $bg_img);
		//Brouillage de l'image.
		$style = array($bg, $bg, $bg, $bg, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis);
		@imagesetstyle($img, $style);

		if ($this->difficulty > 0)
		{
			if ($rand)
			{
				for ($i = 0; $i <= $this->height; $i = ($i + 2))
				{
					@imageline($img, 0, $i, $this->width, $i, IMG_COLOR_STYLED);
				}
			}
			else
			{
				for ($i = $this->height; $i >= 0; $i = ($i - 2))
	            {
	                @imageline($img, 0, $i, $this->width, $i, IMG_COLOR_STYLED);
                }
			}
		}

		##Attribut du code à écrire##
		//Centrage du texte.
		$global_font_size = 24;
		$array_size_ttf = imagettfbbox($global_font_size + 2, 0, $this->font, $this->code);
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($this->width/2) - ($text_width/2);
		$text_y = ($this->height/2) + ($text_height/2);

		preg_match_all('/.{1}/s', $this->code, $matches);
		foreach ($matches[0] as $key => $letter)
		{
			//Allocation des couleurs.
			$index_color = array_rand($array_color);
			list($r, $g, $b) = $array_color[$index_color];
			$text_color = @imagecolorallocate($img, $r, $g, $b);
			list($r, $g, $b) = Captcha::image_color_allocate_dark($array_color[$index_color]);
			$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			$font_size = rand($global_font_size - 4, $global_font_size);
			$angle = rand(-15, 15);
			$move_y = $text_y + rand(-15, 4);
			if ($this->difficulty < 2)
			{
				$angle = 0;
				$move_y = $text_y - 2;
			}

			//Ajout de l'ombre.
			if ($this->difficulty == 4)
			{
				list($r, $g, $b) = Captcha::image_color_allocate_dark($array_color[$index_color], 90, 0.50);
				$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			}
			imagettftext($img, $font_size, $angle, ($text_x + 1), ($move_y + 1), $text_color_dark, $this->font, $letter);

			//Ecriture du code.
			imagettftext($img, $font_size, $angle, $text_x, $move_y, $text_color, $this->font, $letter);
			$array_size_ttf = imagettfbbox($font_size, $angle, $this->font, $this->code);
			$text_width = max(abs($array_size_ttf[2] - $array_size_ttf[0]), 5);
			$text_x += $global_font_size - 6;
		}

		//Bordure
		@imagerectangle($img, 0, 0, $this->width - 1, $this->height - 1, $black);

		##Envoi de l'image##
		imagejpeg($img);
		imagedestroy($img);
	}

	/**
	 * @desc Set the captcha code in the database.
	 */
	public function save_user()
	{
		global $Sql;

		$this->generate_code(); //Mise à jour du code.

		$code = substr(md5(uniqid(mt_rand(), true)), 0, 20);
		$check_user_id = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_VERIF_CODE . " WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		if ($check_user_id == 1)
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_VERIF_CODE . " SET code = '" . $code . "', difficulty = '" . $this->difficulty . "' WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("INSERT INTO " . DB_TABLE_VERIF_CODE . " (user_id, code, difficulty, timestamp) VALUES ('" . $this->user_id . "', '" . $this->code . "', '" . $this->difficulty . "', '" . time() . "')", __LINE__, __FILE__);
		}
	}

	## Private Methods ##
	/**
	* @desc Génère un code aléatoire dépendant de la difficulté.
	*/
	private function generate_code()
	{
		$rand = rand(0,1);

		##Génération du code##
		$words = LangLoader::get_message('_code_dictionnary', 'main');

		switch ($this->difficulty)
		{
			case 0;
			$this->code = $words[array_rand($words)]; //Mot aléatoire
			break;
			case 1:
				$this->code = str_shuffle($words[array_rand($words)]); //Mot mélangé aléatoire.
				break;
			case 2:
				$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire
				break;
			case 3:
			case 4:
				$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire.
				break;
			default:
				$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2)));
		}
		if ($this->difficulty > 0)
		{
			$this->code = substr($this->code, 0, 6);
			$this->code = str_replace(array('l', '1', 'o', '0'), array('', '', '', ''), $this->code);
		}
	}

	/**
	 * @desc Set the captcha's code in the database.
	 */
	private function update_code()
	{
		global $Sql;

		$check_user_id = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_VERIF_CODE . " WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		if ($check_user_id == 1)
		{
		  $Sql->query_inject("UPDATE " . DB_TABLE_VERIF_CODE . " SET code = '" . $this->code . "' WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		}
		else
		{
		  $Sql->query_inject("INSERT INTO " . DB_TABLE_VERIF_CODE . " (user_id, code, difficulty, timestamp) VALUES ('" . $this->user_id . "', '" . $this->code . "', '4', '" . time() . "')", __LINE__, __FILE__);
		}
	}

	/**
	 * @desc Update captcha's instance to allow multiple instance.
	 */
	private function update_instance()
	{
		$this->instance = ++self::$instance_number;
	}

	/**
	 * @desc return a hash computed from the user ip and instance identifier.
	 */
	private function get_user_id() {
		return substr(strhash(USER_IP), 0, 13) . $this->instance;
	}

	/**
	 * @desc Return the darker version of a color passed in argument.
	 * 3D effect, mask_color: 0 for the darkest, 255 for the brightest; similar_color: between 0.40 very different to 0.99 very close.
	 * @param $array_color
	 * @param $mask_color
	 * @param $similar_color
	 * @return array The new darker color.
	 */
	private static function image_color_allocate_dark($array_color, $mask_color = 0, $similar_color = 0.40)
	{
		list($r, $g, $b) = $array_color;
		$rd = round($r * $similar_color) + round($mask_color * (1 - $similar_color));
		$gd = round($g * $similar_color) + round($mask_color * (1 - $similar_color));
		$bd = round($b * $similar_color) + round($mask_color * (1 - $similar_color));

		return array($rd, $gd, $bd);
	}

	## Setters and getters ##
	/**
	* @desc Modifies the level of difficulty to decrypt the code on the captcha image.
	* @param int $difficulty The difficulty :
	*  0: Dictionnary words, regular background, horizontal text.
	*	1: blended word, regular background.
	*	2: blended word + figures, irregular background.
	*	3: blended word + figures, shadows.
	*	4: blended word + figures, shadows.
	*/
	public function set_difficulty($difficulty)	{$this->difficulty = max(0, $difficulty);}
	/**
	 * @desc Modify instance number.
	 * @param int $instance
	 */
	public function set_instance($instance)	{$this->instance = $instance;}
	/**
	 * @desc Modify width of the image.
	 * @param float $width Width of the image.
	 */
	public function set_width($width) {$this->width = $width;}
	/**
	 * @desc Modify height of the image.
	 * @param float $height Height of the image.
	 */
	public function set_height($height)	{$this->height = $height;}

	/**
	 * @desc Modify font used for the text on the image.
	 * @param string $font Font used for the text on the image.
	 */
	public function set_font($font) {$this->font = $font;}

	public function set_html_id($id)
	{
		$this->html_id = $id;
	}

	public function get_instance() {return $this->instance;}
	public function get_width() {return $this->width;}
	public function get_height() {return $this->height;}
	public function get_font() {return $this->font;}
	public function get_difficulty() {return $this->difficulty;}

	public function get_html_id()
	{
		return $this->html_id;
	}
}

?>