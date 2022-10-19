<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 30
 * @since       PHPBoost 1.6 - 2007 08 27
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('NO_ALLOCATE_COLOR', false);
define('NO_DRAW_PERCENT', false);
define('NO_DRAW_LEGEND', false);
define('DRAW_LEGEND', true);
define('NO_DRAW_VALUES', false);
define('DRAW_VALUES', true);

define('FRANKLINBC_TTF', PATH_TO_ROOT . '/kernel/data/fonts/franklinbc.ttf');

class ImagesStats
{
	public  $array_color_stats = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237), array(204, 42, 38), array(53, 144, 189), array(102, 102, 153), array(236, 230, 208), array(213, 171, 1), array(182, 0, 51), array(193, 73, 0), array(25, 119, 128), array(182, 181, 177), array(102, 133, 237));	//Tableau des couleurs.
	private $nbr_color = 14;
	public  $data_stats; //Tableau des données.
	public $nbr_entry; //Nombre d'entrée à traiter.
	public $array_allocated_color = array(); //Tableau des couleurs allouées.
	public $color_index = 0; //Couleur courante.
	private $decimal = 1; //Arrondi

	/**
	 * @desc Load data for the charts.
	 * @param array $array_stats Associative array, array key is the legend for the value.
	 * @param string $draw_type Type of chart.
	 * @param int $decimal Round precision.
	 */
	public function load_data($array_stats, $draw_type = 'ellipse', $decimal = 1)
	{
		$this->decimal = $decimal;
		if ($draw_type == 'ellipse')
		{
			//Nombre total d'entrées
			$this->nbr_entry = array_sum($array_stats);
			if ($this->nbr_entry == 0)
				$this->data_stats = array(LangLoader::get_message('common.other', 'common-lang') => 360);
			else
			{
				//On classe le tableau par ordre décroissant de hits
				arsort($array_stats);

				//Conversion valeur vers angle.
				$this->data_stats = array_map(array($this, 'value_to_angle'), $array_stats);
			}
		}
		elseif ($draw_type == 'histogram')
		{
			ksort($array_stats);
			$this->data_stats = $array_stats;
		}
		else
			$this->data_stats = array(LangLoader::get_message('common.other', 'common-lang') => 360);
	}

	/**
	 * @desc Drawn an ellipse.
	 * @param int $w_arc Ellipse width.
	 * @param int $h_arc Ellipse height.
	 * @param string $img_cache Name of the image store in cache.
	 * @param int $height_3d Height for the 3d ellipse effect.
	 * @param boolean $draw_percent Display percents around the ellipse.
	 * @param boolean $draw_legend Display a legend next to the ellipse.
	 * @param string $font_size Font size used for the legend.
	 * @param string $font Font type used for the legend.
	 * @return Return true if image has been succefully created, false otherwise and create an error image.
	 */
	public function draw_ellipse($w_arc, $h_arc, $img_cache = '', $height_3d = 20, $draw_percent = true, $draw_legend = true, $font_size = 10, $font = FRANKLINBC_TTF)
	{
		if (@extension_loaded('gd'))
		{
			$w_arc = (int)$w_arc;
			$h_arc = (int)$h_arc;
			$w_ellipse = (int)($w_arc/2);
			$h_ellipse = (int)($h_arc/2);
			$height_3d = (int)$height_3d;

			list($x_ellipse, $y_ellipse, $x_legend_extend, $y_legend_extend) = array(0, 0, 0, 0);
			if ($draw_legend) //Tracé de la légende de l'ellipse.
			{
				$x_legend_extend = 260;
				$y_legend_extend = 120;
			}
			if ($draw_percent) //Tracé des pourcentages autour de l'ellipse, calcul du décallage horizontal/vertical de l'ellipse.
			{
				$array_size_ttf = imagettfbbox($font_size, 0, $font, '99.9%');
				$x_ellipse = (int)abs($array_size_ttf[2] - $array_size_ttf[0]) + 5;
				$x_ellipse += ($x_ellipse * 10)/100; //Marge de 10% supplémentaire.
				$y_ellipse = (int)abs($array_size_ttf[7] - $array_size_ttf[1]) + 30;
				$y_ellipse += ($y_ellipse * 12)/100;
			}

			// Création de l'image
			$image = imagecreatetruecolor($w_arc + $x_legend_extend, $h_arc + $height_3d + $y_legend_extend);
			$background = imagecolorallocate($image, 243, 243, 243);
			$border = imagecolorallocate($image, 117, 119, 131);
			$black = imagecolorallocate($image, 0, 0, 0);
			imagefilledrectangle($image, 0, 0, $w_arc + $x_legend_extend, $h_arc + $height_3d + $y_legend_extend, $border);
			imagefilledrectangle($image, 1, 1, $w_arc + $x_legend_extend - 2, $h_arc + $height_3d + $y_legend_extend - 2, $background);

			// Création de l'effet 3D
			for ($i = ($h_ellipse + $height_3d); $i >= $h_ellipse; $i--)
			{
				$angle = 0;
				$this->color_index = 0;
				foreach ($this->data_stats as $name_value => $angle_value)
				{
					$get_color = $this->array_allocated_color[$this->image_color_allocate_dark($image) . 'dark'];
					if ($angle_value > 5)
						imagefilledarc($image, $w_ellipse + $x_ellipse, $i + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_color, IMG_ARC_NOFILL);
					$angle += $angle_value;
				}
			}

			//Surface.
			$this->color_index = 0;
			$angle = 0;
			$angle_other = 0;
			foreach ($this->data_stats as $name_value => $angle_value)
			{
				if ($angle_value > 5 && $draw_percent)
				{
					$get_color = $this->array_allocated_color[$this->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
					$this->color_index--;
					$get_shadow_color = $this->array_allocated_color[$this->image_color_allocate_dark(false, NO_ALLOCATE_COLOR) . 'dark'];
					imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_color, IMG_ARC_PIE);
					imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_shadow_color, IMG_ARC_NOFILL);

					//Calcul des coordonées cartésiennes.
					$angle_tmp = (int)((2*$angle + $angle_value) / 2);
					$angle_string = deg2rad($angle_tmp);
					$x_string =(int)(($w_ellipse * 1.2) * cos($angle_string) + $w_ellipse + $x_ellipse);
					$y_string = (int)(($h_ellipse * 1.2) * sin($angle_string) + $h_ellipse + $y_ellipse);

					//Texte
					$text = ($angle_value != 360) ? NumberHelper::round(($angle_value/3.6), 1) . '%' : '100%';

					//Centrage du texte.
					$array_size_ttf = imagettfbbox($font_size, 0, $font, $text);
					$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
					$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);

					$text_x = $x_string - ($text_width/2);
					$text_y = ($angle_tmp >= 0 && $angle_tmp <= 180 ) ? $y_string + ($text_height/2) + $height_3d : $y_string + ($text_height/2);
					imagettftext($image, $font_size, 0, intval($text_x), intval($text_y), $black, $font, $text);

					$angle += $angle_value;
				}
				else
					$angle_other += $angle_value;
			}

			if (!empty($angle_other))
			{
				$get_color = $this->array_allocated_color[$this->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
				$this->color_index--;
				$get_shadow_color = $this->array_allocated_color[$this->image_color_allocate_dark(false, NO_ALLOCATE_COLOR) . 'dark'];
				imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_other), $get_color, IMG_ARC_PIE);
				imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_other), $get_shadow_color, IMG_ARC_NOFILL);
			}

			//Légende
			if ($draw_legend) //Tracé de la légende de l'ellipse.
			{
				$x_legend_extend = $w_arc + (2 * $x_ellipse) + 10;
				$y_legend_extend = 10;

				$this->color_index = 0;
				$i = 0;
				foreach ($this->data_stats as $name_value => $angle_value)
				{
					$get_color = $this->array_allocated_color[$this->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
					if ($i < 8)
					{
						//Carré de couleur.
						imagefilledrectangle($image, $x_legend_extend + 6, $y_legend_extend + (16*$i) + 7, $x_legend_extend + 18, $y_legend_extend + (16*$i) + 19, $black);
						imagefilledrectangle($image, $x_legend_extend + 7, $y_legend_extend + (16*$i) + 8, $x_legend_extend + 17, $y_legend_extend + (16*$i) + 18, $get_color);

						//Texte
						$text = TextHelper::ucfirst(TextHelper::substr($name_value, 0, 14)) . ' (' . (($angle_value != 360) ? NumberHelper::round(($angle_value/3.6), 1) . '%' : '100%') . ')';

						imagettftext($image, $font_size, 0, intval($x_legend_extend + 24), intval($y_legend_extend + (16*$i) + 17), $black, $font, $text);
						$i++;
					}
					else
						break;
				}
			}

			//Affichage de l'image
			header('Content-type: image/png');
			if (!empty($img_cache))
				imagepng($image, $img_cache);
			imagepng($image);
			imagedestroy($image);

			return true;
		}
		else
		{
			$this->create_pics_error($w_arc, $h_arc, $font_size, $font);
			return false;
		}
	}

	/**
	 * @desc Draw a histogram
	 * @param int $w_histo Histogram width.
	 * @param int $h_histo Histogram height.
	 * @param string $img_cache Name of the image store in cache.
	 * @param int $scale_legend Height for the 3d histogram effect.
	 * @param boolean $draw_legend Display a legend next the histogram
	 * @param boolean $draw_values Display value under the histogram
	 * @param string $font_size Font size used for the legend.
	 * @param string $font Font type used for the legend.
	 * @return Return true if image has been succefully created, false otherwise and create an error image.
	 */
	public function draw_histogram($w_histo, $h_histo, $img_cache = '', $scale_legend = array(), $draw_legend = true, $draw_values = true, $font_size = 10, $font = FRANKLINBC_TTF)
	{
		if (@extension_loaded('gd'))
		{
			$w_histo = (int)$w_histo;
			$h_histo = (int)$h_histo;
			$max_element = max($this->data_stats);
			$max_element = max(array($max_element, 1));
			list($x_histo, $y_histo, $x_legend_extend, $y_legend_extend) = array(0, 0, 0, 0);
			if ($draw_legend) //Tracé de la légende.
			{
				$x_legend_extend = 172;
				$y_legend_extend = 0;
			}

			// Création de l'image
			$image = imagecreatetruecolor($w_histo + $x_legend_extend, $h_histo + $y_legend_extend);
			$background = imagecolorallocate($image, 243, 243, 243);
			$border = imagecolorallocate($image, 117, 119, 131);
			$black = imagecolorallocate($image, 0, 0, 0);
			imagefilledrectangle($image, 0, 0, $w_histo + $x_legend_extend, $h_histo + $y_legend_extend, $border);
			imagefilledrectangle($image, 1, 1, $w_histo + $x_legend_extend - 3, $h_histo + $y_legend_extend - 3, $background);

			//Légende
			if ($draw_legend) //Tracé de la légende.
			{
				$white = imagecolorallocate($image, 255, 255, 255);
				$shadow = imagecolorallocate($image, 125, 121, 118);
				$x_legend_extend = $w_histo + (2 * $x_histo) + 10;
				$y_legend_extend = 10;
				$width_legend = 150;
				$height_legend = 138;
				imagefilledrectangle($image, $x_legend_extend - 4, $y_legend_extend + 2, $x_legend_extend + $width_legend - 2, $y_legend_extend + $height_legend + 4, $shadow);
				imagefilledrectangle($image, $x_legend_extend - 1, $y_legend_extend - 1, $x_legend_extend + $width_legend + 1, $y_legend_extend + $height_legend + 1, $black);
				imagefilledrectangle($image, $x_legend_extend, $y_legend_extend, $x_legend_extend + $width_legend, $y_legend_extend + $height_legend, $white);

				$this->color_index = 0;
				$i = 0;
				foreach ($this->data_stats as $name_value => $value)
				{
					$get_color = $this->array_allocated_color[$this->image_color_allocate_dark($image)];
					if ($i < 8)
					{
						//Carré de couleur.
						imagerectangle($image, $x_legend_extend + 6, $y_legend_extend + (16*$i) + 7, $x_legend_extend + 18, $y_legend_extend + (16*$i) + 19, $black);
						imagefilledrectangle($image, $x_legend_extend + 7, $y_legend_extend + (16*$i) + 8, $x_legend_extend + 17, $y_legend_extend + (16*$i) + 18, $get_color);

						//Texte
						imagettftext($image, $font_size, 0, intval($x_legend_extend + 24), intval($y_legend_extend + (16*$i) + 17), $black, $font, $name_value);
						$i++;
					}
					else
						break;
				}
			}

			//Génération de l'histogramme.
			$margin = 21;
			$array_size_ttf = imagettfbbox($font_size, 0, $font, $max_element);
			$x_histo = (int)(abs($array_size_ttf[2] - $array_size_ttf[0]) + $margin);
			$y_histo = (int)(abs($array_size_ttf[7] - $array_size_ttf[1]) + $margin);
			$h_histo_content = $h_histo - $y_histo - $margin;
			$w_histo_content = $w_histo - $margin - $x_histo;

			//Font de l'histogramme.
			$histo_background = imagecolorallocate($image, 255, 255, 255);
			$border_dashed = imagecolorallocate($image, 199, 199, 199);
			$border_scale = imagecolorallocate($image, 17, 15, 112);
			imagerectangle($image, $x_histo - 1, $margin, $w_histo - ($margin + 1), $h_histo - $y_histo + 1, $border_scale);
			imagefilledrectangle($image, $x_histo, $margin, $w_histo - $margin, $h_histo - $y_histo, $histo_background);

			//Echelle verticale de l'histogramme.
			$array_scale = $this->generate_scale($max_element);
			$scale_pos = $margin;
			$scale_iteration = NumberHelper::round(($h_histo_content+1)/15, 2);
			$j = 0;
			for ($i = 0; $i < 16; $i++)
			{
				if (($i%5) == 0)
				{
					if ($i < 15 )
					{
						//Lignes pointillées
						imagesetstyle($image, array($border_dashed, $border_dashed, $border_dashed, $histo_background, $histo_background, $histo_background));
						imageline($image, $x_histo, $scale_pos, $w_histo - $margin, $scale_pos, IMG_COLOR_STYLED);
					}

					//Texte
					$array_size_ttf = imagettfbbox($font_size, 0, $font, $array_scale[$j]);
					$x_text = abs($array_size_ttf[2] - $array_size_ttf[0]) + 6;
					$y_text = abs($array_size_ttf[7] - $array_size_ttf[1]);
					imagettftext($image, $font_size, 0, intval($x_histo - $x_text), intval($scale_pos + ($y_text/2)), $black, $font, $array_scale[$j]);

					$j++;
					$separator = 3;
				}
				else
					$separator = 1;

				if ($i < 15)
					imageline($image, $x_histo, $scale_pos, $x_histo + $separator, $scale_pos, $border_scale);
				$scale_pos += $scale_iteration;
			}

			//Echelle horizontale, et tracé des barres.
			$this->color_index = 5;
			$color_bar = imagecolorallocate($image, 68, 113, 165);
			$color_bar_dark = imagecolorallocate($image, 99, 136, 177);
			$space_bar = NumberHelper::round(($w_histo_content - 4)/count($this->data_stats), 0);
			$margin_bar = $space_bar*18/100;
			$width_bar = $space_bar - (2*$margin_bar);
			if ($max_element == 1)
				$max_height = ($h_histo_content * 33)/100;
			else if ($max_element == 2)
				$max_height = ($h_histo_content * 66)/100;
			else
				$max_height = ($h_histo_content * 80)/100;
			$i = 0;
			foreach ($this->data_stats as $name_value => $value)
			{
				$height_bar = ($value * 100/$max_element) * $max_height / 100;
				$x_bar = $x_histo + 4 + ($space_bar*$i) + $margin_bar;
				$x2_bar = $x_bar + $space_bar - ($margin_bar*3);
				$x_bar += $space_bar*5/100;
				$x2_bar -= $space_bar*5/100;
				$y_bar = ($margin + $h_histo_content) - $height_bar;
				$y2_bar = $margin + $h_histo_content;

				if ($value != 0)
				{
					//Bordure.
					imagerectangle($image, intval($x_bar + $width_bar/3), $y_bar - 4, intval($x2_bar + $width_bar/3 + 1), $y2_bar, $black);
					//Barre sombre
					imagefilledrectangle($image, intval($x_bar + $width_bar/3), $y_bar - 3, intval($x2_bar + $width_bar/3), $y2_bar, $color_bar_dark);
					//Bordure.
					imagerectangle($image, $x_bar - 1, $y_bar - 1, $x2_bar + 1, $y2_bar + 1, $black);
					//Barre
					imagefilledrectangle($image, $x_bar, $y_bar, $x2_bar, $y2_bar, $color_bar);
					//Chapeau
					$polygon_point = array(
						intval($x_bar + $width_bar/3), $y_bar - 4,
						intval($x2_bar + $width_bar/3 + 1), $y_bar - 4,
						$x2_bar + 1, $y_bar - 1,
						$x_bar - 1, $y_bar - 1
					);
					imagefilledpolygon($image, $polygon_point, 4, $color_bar_dark);
					$polygon_point = array(
						intval($x_bar + $width_bar/3), $y_bar - 4,
						intval($x2_bar + $width_bar/3 + 1), $y_bar - 4,
						$x2_bar + 1, $y_bar - 1,
						$x_bar - 1, $y_bar - 1
					);
					imagepolygon($image, $polygon_point, 4, $black);

					if ($draw_values) //Texte, valeur
					{
						$array_size_ttf = imagettfbbox($font_size, 0, $font, $value);
						$x_text = abs($array_size_ttf[2] - $array_size_ttf[0]);
						$y_text = abs($array_size_ttf[7] - $array_size_ttf[1]);
						imagettftext($image, $font_size, 0, intval(($x_bar + $x2_bar + ($width_bar/3))/2 - ($x_text/2)), intval( $y_bar - $y_text), $black, $font, $value);
					}
				}
				//Texte, info
				$array_size_ttf = imagettfbbox($font_size, 0, $font, $name_value);
				$x_text = abs($array_size_ttf[2] - $array_size_ttf[0]);
				$y_text = abs($array_size_ttf[7] - $array_size_ttf[1]);
				imagettftext($image, $font_size, 0, intval(($x_bar + $x2_bar + ($width_bar/3))/2 - ($x_text/2)), intval($margin + $h_histo_content + $y_text + 4), $black, $font, $name_value);

				$i++;
			}

			//Légende des axes
			$scale_legend = array_map("ucfirst", $scale_legend);
			$scale_legend = array_map(function($a) {return "("  . $a . ")";}, $scale_legend);
			if (isset($scale_legend[0]))
			{
				$array_size_ttf = imagettfbbox($font_size, 0, $font, $scale_legend[0]);
				$x_text = abs($array_size_ttf[2] - $array_size_ttf[0]);
				$y_text = abs($array_size_ttf[7] - $array_size_ttf[1]);
				imagettftext($image, $font_size, 0, intval($x_histo + $w_histo_content - $x_text + ($margin/2)), intval($w_histo - $h_histo_content + ($y_text/2)), $black, $font, $scale_legend[0]);
			}
			if (isset($scale_legend[1]))
			{
				$array_size_ttf = imagettfbbox($font_size, 0, $font, $scale_legend[1]);
				$x_text = abs($array_size_ttf[2] - $array_size_ttf[0]);
				$y_text = abs($array_size_ttf[7] - $array_size_ttf[1]);
				imagettftext($image, $font_size, 0, intval($margin/2), intval($y_text), $black, $font, $scale_legend[1]);
			}

			//Affichage de l'image
			header('Content-type: image/png');
			if (!empty($img_cache))
				imagepng($image, $img_cache);
			imagepng($image);
			imagedestroy($image);

			return true;
		}
		else
		{
			$this->create_pics_error($w_histo, $h_histo, $font_size, $font);
			return false;
		}
	}

	/**
	 * @desc Draw graph, not yet implemented.
	 */
	public function draw_graph()
	{
	}

	/**
	 * @desc convert value to angle
	 * @param int $value the value to convert.
	 * @return int angle
	 */
	private function value_to_angle($value)
	{
		return NumberHelper::round(($value * 360)/$this->nbr_entry, $this->decimal);
	}

	/**
	 * @desc Allocate a darker color than the color given.
	 * @param resource $image Image concerned.
	 * @param resource $mask_color Set 0 for darker, 255 for brighter
	 * @param resource $similar_color Set 0.40 for a different color and 0.99 for a very similar.
	 * @return int angle
	 */
	public function image_color_allocate_dark($image, $allocate = true, $mask_color = 0, $similar_color = 0.50)
	{
		if ($this->color_index == $this->nbr_color)
			$this->color_index = 0;

		if (!isset($this->array_allocated_color[$this->color_index]))
		{
			list($r, $g, $b) = $this->array_color_stats[$this->color_index];
			$rd = round($r * $similar_color) + round($mask_color * (1 - $similar_color));
			$gd = round($g * $similar_color) + round($mask_color * (1 - $similar_color));
			$bd = round($b * $similar_color) + round($mask_color * (1 - $similar_color));

			$this->array_allocated_color[$this->color_index] = $allocate ? imagecolorallocate($image, $r, $g, $b) : array($r, $g, $b); // Allocation de la couleur de surface.
			$this->array_allocated_color[$this->color_index . 'dark'] = $allocate ? imagecolorallocate($image, $rd, $gd, $bd) : array($rd, $gd, $bd); // Allocation de la couleur de l'effet 3d.
		}
		$this->color_index++;

		return ($this->color_index - 1);
	}

	/**
	 * @desc Generate a scale
	 * @param array $array_scale List of element
	 * @param resource $max_element Maximal element int the array_scale list
	 */
	private function generate_scale($max_element)
	{
		$array_scale = array();
		$max_element += ($max_element * 20/100);
		while (($max_element%3) != 0)
			$max_element++;

		$scale = $max_element;
		$scale_iteration = (int)($max_element/3);
		for ($i = 0; $i < 4; $i++)
		{
			$array_scale[$i] = NumberHelper::round(abs($scale), 0);
			$scale -= $scale_iteration;
		}
		return $array_scale;
	}

	/**
	 * @desc Round to the next dozen
	 * @param int $number Number to round
	 * @param boolean $demi_dozen Round to the half dozen.
	 * @return int The number rounded.
	 */
	private function number_round_dozen($number, $demi_dozen = true)
	{
		$unit = $number % 10;
		$number = NumberHelper::round($number, 1) * 10;
		$decimal = $unit + ($number % 10)/10;
		$number /= 10;

		if ($demi_dozen)
		{
			if ($decimal < 2.5)
				$number = $number - $decimal;
			elseif ($decimal >= 2.5 && $decimal <= 7.5)
				$number = $number - $decimal + 5;
			else
				$number = $number - $decimal + 10;
		}
		else
		{
			if ($decimal < 5)
				$number = $number - $decimal;
			else
				$number = $number - $decimal + 10;
		}

		return NumberHelper::round($number, 0);
	}

	/**
	 * @desc Create error image
	 * @param int $width Image width
	 * @param int $height Image height
	 * @param int $font_size Font size used on the image.
	 * @param int $font Font type used on the image.
	 */
	private function create_pics_error($width, $height, $font_size, $font)
	{
		$thumbtail = @imagecreate($width, $height);
		$background = @imagecolorallocate($thumbtail, 255, 255, 255);
		$text_color = @imagecolorallocate($thumbtail, 0, 0, 0);

		//Centrage du texte.
		$array_size_ttf = @imagettfbbox($font_size, 0, $font, 'Error Image');
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($width/2) - ($text_width/2);
		$text_y = ($height/2) + ($text_height/2);

		//Ecriture du code.
		@imagettftext($thumbtail, $font_size, 0, intval($text_x), intval($text_y), $text_color, $font, 'Error Image');

		//Affichage de l'image
		header('Content-type: image/png');
		imagepng($thumbtail);
		imagedestroy($thumbtail);
	}
}
?>
