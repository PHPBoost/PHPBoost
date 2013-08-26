<?php 
/*##################################################
 *                            PHPBoostCaptcha.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class PHPBoostCaptcha extends Captcha
{
	const CAPTCHA_VERY_EASY = 0;
	const CAPTCHA_EASY = 1;
	const CAPTCHA_NORMAL = 2;
	const CAPTCHA_HARD = 3;
	const CAPTCHA_VERY_HARD = 4;
	
	private $code;
	private $user_id;
	private $sql_querier;
		
	public function __construct()
	{
		$this->set_options(new PHPBoostCaptchaOptions());
		$this->sql_querier = PersistenceContext::get_sql();
		$this->user_id = $this->get_user_id();
	}
	
	public function get_name()
	{
		return 'PHPBoostCaptcha';
	}
		
	public function is_available()
	{
		$server_configuration = new ServerConfiguration();
		return $server_configuration->has_gd_library();
	}
	
	public function is_valid()
	{
		if (!$this->is_available() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			return true;
		}

		$get_code = retrieve(POST, $this->get_html_id(), '', TSTRING_UNCHANGE);
		$captcha = $this->sql_querier->query_array(DB_TABLE_VERIF_CODE, 'code', 'difficulty', "WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);

		$this->sql_querier->query_inject("DELETE FROM " . DB_TABLE_VERIF_CODE . " WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);

		if (!empty($captcha['code']) && $captcha['code'] == $get_code && $captcha['difficulty'] == $this->get_options()->get_difficulty())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function display()
	{
		$tpl = new FileTemplate('PHPBoostCaptcha/display.tpl');
		$tpl->put_all(array(
			'CAPTCHA_WIDTH' => $this->get_options()->get_width(),
			'CAPTCHA_HEIGHT' => $this->get_options()->get_height(),
			'CAPTCHA_FONT' => $this->get_options()->get_font(),
			'CAPTCHA_DIFFICULTY' => $this->get_options()->get_difficulty(),
			'HTML_ID' => $this->get_html_id()
		));
		return $tpl->render();
	}
	
	public function display_image()
	{
		$code = $this->get_code();
		$width = $this->get_options()->get_width();
		$height = $this->get_options()->get_height();
		$difficulty = $this->get_options()->get_difficulty();
		$font = $this->get_options()->get_font();
		
		$rand = rand(0,1);

		switch ($this->get_options()->get_difficulty())
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
			$img = @imagecreate($width, $height);
		}
		else
		{
			$img = @imagecreatetruecolor($width, $height);
		}

		//Choix aléatoire de couleur, et suppression du tableau pour éviter une réutilisation pour le texte.
		$bg_bis_index_color = array_rand($array_color);
		list($r, $g, $b) = self::image_color_allocate_dark($array_color[$bg_bis_index_color], 150, 0.70); //Assombrissement de la couleur de fond.
		$bg_img = @imagecolorallocate($img, $r, $g, $b);
		if ($difficulty < 3)
		{
			unset($array_color[$bg_bis_index_color]);
		}

		$bg_index_color = array_rand($array_color);
		list($r, $g, $b) = $array_color[$bg_index_color];
		$bg = @imagecolorallocate($img, $r, $g, $b);
		if ($difficulty < 3)
		{
			unset($array_color[$bg_index_color]);
		}

		$bg_bis_index_color = array_rand($array_color);
		list($r, $g, $b) = $array_color[$bg_bis_index_color];
		$bg_bis = @imagecolorallocate($img, $r, $g, $b);
		if ($difficulty < 3)
		{
			unset($array_color[$bg_bis_index_color]);
		}

		$black = @imagecolorallocate($img, 0, 0, 0);

		##Création de l'arrère plan
		//Couleur de fond
		@imagefilledrectangle($img, 0, 0, $width, $height, $bg_img);
		//Brouillage de l'image.
		$style = array($bg, $bg, $bg, $bg, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis);
		@imagesetstyle($img, $style);

		if ($difficulty > 0)
		{
			if ($rand)
			{
				for ($i = 0; $i <= $height; $i = ($i + 2))
				{
					@imageline($img, 0, $i, $width, $i, IMG_COLOR_STYLED);
				}
			}
			else
			{
				for ($i = $height; $i >= 0; $i = ($i - 2))
	            {
	                @imageline($img, 0, $i, $width, $i, IMG_COLOR_STYLED);
                }
			}
		}

		##Attribut du code à écrire##
		//Centrage du texte.
		$global_font_size = 24;
		$array_size_ttf = imagettfbbox($global_font_size + 2, 0, $font, $code);
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($width/2) - ($text_width/2);
		$text_y = ($height/2) + ($text_height/2);

		preg_match_all('/.{1}/s', $code, $matches);
		foreach ($matches[0] as $key => $letter)
		{
			//Allocation des couleurs.
			$index_color = array_rand($array_color);
			list($r, $g, $b) = $array_color[$index_color];
			$text_color = @imagecolorallocate($img, $r, $g, $b);
			list($r, $g, $b) = self::image_color_allocate_dark($array_color[$index_color]);
			$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			$font_size = rand($global_font_size - 4, $global_font_size);
			$angle = rand(-15, 15);
			$move_y = $text_y + rand(-15, 4);
			if ($difficulty < 2)
			{
				$angle = 0;
				$move_y = $text_y - 2;
			}

			//Ajout de l'ombre.
			if ($difficulty == 4)
			{
				list($r, $g, $b) = self::image_color_allocate_dark($array_color[$index_color], 90, 0.50);
				$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
			}
			imagettftext($img, $font_size, $angle, ($text_x + 1), ($move_y + 1), $text_color_dark, $font, $letter);

			//Ecriture du code.
			imagettftext($img, $font_size, $angle, $text_x, $move_y, $text_color, $font, $letter);
			$array_size_ttf = imagettfbbox($font_size, $angle, $font, $code);
			$text_width = max(abs($array_size_ttf[2] - $array_size_ttf[0]), 5);
			$text_x += $global_font_size - 6;
		}

		//Bordure
		@imagerectangle($img, 0, 0, $width - 1, $height - 1, $black);

		##Envoi de l'image##
		imagejpeg($img);
		imagedestroy($img);
	}

	private function get_code()
	{
		if ($this->code === null)
		{
			$rand = rand(0,1);
			$words = LangLoader::get_message('_code_dictionnary', 'main');
	
			switch ($this->get_options()->get_difficulty())
			{
				case self::CAPTCHA_VERY_EASY;
					$this->code = $words[array_rand($words)]; //Mot aléatoire
					break;
				case self::CAPTCHA_EASY:
					$this->code = str_shuffle($words[array_rand($words)]); //Mot mélangé aléatoire.
					break;
				case self::CAPTCHA_NORMAL:
					$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire
					break;
				case self::CAPTCHA_HARD:
				case self::CAPTCHA_VERY_HARD:
					$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2))); //Mot + nombres mélangé aléatoire.
					break;
				default:
					$this->code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2)));
			}
			
			if ($this->get_options()->get_difficulty() > self::CAPTCHA_VERY_EASY)
			{
				$this->code = substr($this->code, 0, 6);
				$this->code = str_replace(array('l', '1', 'o', '0'), array('', '', '', ''), $this->code);
			}
		}
		return $this->code;
	}
	
	private function update_code()
	{
		$check_user_id = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_VERIF_CODE . " WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		if ($check_user_id == 1)
		{
		  $this->sql_querier->query_inject("UPDATE " . DB_TABLE_VERIF_CODE . " SET code = '" . $this->get_code() . "' WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		}
		else
		{
		  $this->sql_querier->query_inject("INSERT INTO " . DB_TABLE_VERIF_CODE . " (user_id, code, difficulty, timestamp) VALUES ('" . $this->user_id . "', '" . $this->get_code() . "', '" . $this->get_options()->get_difficulty() . "', '" . time() . "')", __LINE__, __FILE__);
		}
	}
	
	private static function image_color_allocate_dark($array_color, $mask_color = 0, $similar_color = 0.40)
	{
		list($r, $g, $b) = $array_color;
		$rd = round($r * $similar_color) + round($mask_color * (1 - $similar_color));
		$gd = round($g * $similar_color) + round($mask_color * (1 - $similar_color));
		$bd = round($b * $similar_color) + round($mask_color * (1 - $similar_color));

		return array($rd, $gd, $bd);
	}
	
	private function get_user_id() 
	{
		return substr(KeyGenerator::string_hash(AppContext::get_current_user()->get_ip()), 0, 13);
	}
}
?>