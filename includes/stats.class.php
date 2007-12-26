<?php
/*##################################################
 *                             stats.class.php
 *                            -------------------
 *   begin                : August 27, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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

define('NO_ALLOCATE_COLOR', false);
define('NO_DRAW_PERCENT', false);

class Stats
{	
	//Tableau des couleurs.
	var $array_color_stats = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237), array(204, 42, 38), array(53, 144, 189), array(102, 102, 153), array(236, 230, 208), array(213, 171, 1), array(182, 0, 51), array(193, 73, 0), array(25, 119, 128), array(182, 181, 177), array(102, 133, 237));	
	var $nbr_color = 15;
	var $data_stats; //Tableau des données.
	var $nbr_entry; //Nombre d'entrée à traiter.
	var $array_allocated_color = array(); //Tableau des couleurs allouées.
	var $color_index = 0; //Couleur courante.
	var $decimal = 1; //Arrondi
	
	//Constructeur
	function Stats() 
	{
	}
	
	//Chargement des données.
	function load_statsdata($array_stats, $draw_type = 'ellipse', $decimal = 1)
	{
		global $LANG;
		if( $draw_type == 'ellipse' )
		{				
			//Nombre total d'entrées			
			$this->nbr_entry = array_sum($array_stats);
			$this->decimal = $decimal;
			if( $this->nbr_entry == 0 )
				$this->data_stats = array($LANG['other'] => 360);
			else
			{
				//On classe le tableau par ordre décroissant de hits
				arsort($array_stats);
					
				//Conversion valeur vers angle.
				$this->data_stats = array_map(array($this, 'value_to_angle'), $array_stats);
			}
		}
		else
			$data_stats = array($LANG['other'] => 360);
	}		

	//Conversion valeur vers angle.
	function value_to_angle($value)
	{
		return $this->number_round(($value * 360)/$this->nbr_entry, $this->decimal);
	}
		
	//Allocation de la couleur et calcul de la version sombre. Paramètres couleur de l'effet 3D, mask_color: 0 pour sombre, 255 pour lumineux; similar_color: entre 0.40 (très différents et 0.99 très proche.
	function imagecolorallocatedark($image, $allocate = true, $mask_color = 0, $similar_color = 0.50)
	{
		if( $this->color_index == $this->nbr_color )
			$this->color_index = 0;
			
		if( !isset($this->array_allocated_color[$this->color_index]) )
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
	
	//Graphique camenbert en ellipse.
	function draw_ellipse($w_arc, $h_arc, $img_cache = '', $height_3d = 20, $draw_percent = true, $draw_legend = true, $font_size = 10, $font = '../includes/data/fonts/franklinbc.ttf')
	{
		if( @extension_loaded('gd') && version_compare(phpversion(), '4.0.6', '>=') )
		{			
			$w_ellipse = $w_arc/2;
			$h_ellipse = $h_arc/2;			
			
			list($x_ellipse, $y_ellipse, $x_legend_extend, $y_legend_extend) = array(0, 0, 0, 0);
			if( $draw_legend ) //Tracé de la légende de l'ellipse.	
			{
				$x_legend_extend = 260;
				$y_legend_extend = 120;
			}
			if( $draw_percent ) //Tracé des pourcentages autour de l'ellipse, calcul du décallage horizontal/vertical de l'ellipse.
			{
				$array_size_ttf = imagettfbbox($font_size, 0, $font, '99.9%');
				$x_ellipse = abs($array_size_ttf[2] - $array_size_ttf[0]) + 5;
				$x_ellipse += ($x_ellipse * 10)/100; //Marge de 10% supplémentaire.
				$y_ellipse = abs($array_size_ttf[7] - $array_size_ttf[1]) + 30;
				$y_ellipse += ($y_ellipse * 12)/100;
			}
			// Création de l'image
			$image = imagecreatetruecolor($w_arc + $x_legend_extend, $h_arc + $height_3d + $y_legend_extend);
			$background = imagecolorallocate($image, 243, 243, 243);
			$border = imagecolorallocate($image, 117, 119, 131);			
			$border_ellipse = imagecolorallocate($image, 128, 128, 128);			
			$black = imagecolorallocate($image, 0, 0, 0);
			imagefilledrectangle($image, 0, 0, $w_arc + $x_legend_extend, $h_arc + $height_3d + $y_legend_extend, $border);				
			imagefilledrectangle($image, 1, 1, $w_arc + $x_legend_extend - 3, $h_arc + $height_3d + $y_legend_extend - 2, $background);				
			
			// Création de l'effet 3D
			for($i = ($h_ellipse + $height_3d); $i >= $h_ellipse; $i--) 
			{
				$angle = 0;
				$this->color_index = 0;
				foreach($this->data_stats as $name_value => $angle_value)
				{					
					$get_color = $this->array_allocated_color[$this->imagecolorallocatedark($image) . 'dark'];
					imagefilledarc($image, $w_ellipse + $x_ellipse, $i + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_color, IMG_ARC_NOFILL);
					$angle += $angle_value;
				}
			}
			
			//Surface.
			$this->color_index = 0;
			$angle = 0;
			foreach($this->data_stats as $name_value => $angle_value)
			{					
				$get_color = $this->array_allocated_color[$this->imagecolorallocatedark(false, NO_ALLOCATE_COLOR)];
				$this->color_index--;
				$get_shadow_color = $this->array_allocated_color[$this->imagecolorallocatedark(false, NO_ALLOCATE_COLOR) . 'dark'];
				imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_color, IMG_ARC_PIE);
				imagefilledarc($image, $w_ellipse + $x_ellipse, $h_ellipse + $y_ellipse, $w_arc, $h_arc, $angle, ($angle + $angle_value), $get_shadow_color, IMG_ARC_NOFILL);
				if( $angle_value > 10 && $draw_percent )
				{					
					//Calcul des coordonées cartésiennes.
					$angle_tmp = (2*$angle + $angle_value) / 2;
					$angle_string = deg2rad($angle_tmp);
					$x_string = ($w_ellipse * 1.2) * cos($angle_string) + $w_ellipse + $x_ellipse;
					$y_string = ($h_ellipse * 1.2) * sin($angle_string) + $h_ellipse + $y_ellipse;				
					
					//Texte
					$text = ($angle_value != 360) ? $this->number_round(($angle_value/3.6), 1) . '%' : '100%';
					
					//Centrage du texte.	
					$array_size_ttf = imagettfbbox($font_size, 0, $font, $text);
					$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
					$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
					
					$text_x = $x_string - ($text_width/2);
					$text_y = ($angle_tmp >= 0 && $angle_tmp <= 180 ) ? $y_string + ($text_height/2) + $height_3d : $y_string + ($text_height/2);
					imagettftext($image, $font_size, 0, $text_x, $text_y, $black, $font, $text);
				}
				$angle += $angle_value;
			}
			
			//Légende
			if( $draw_legend ) //Tracé de la légende de l'ellipse.	
			{				
				$white = imagecolorallocate($image, 255, 255, 255);
				$shadow = imagecolorallocate($image, 125, 121, 118);
				$x_legend_extend = $w_arc + (2 * $x_ellipse) + 10;
				$y_legend_extend = 10;
				$width_legend = 150;
				$height_legend = 138;
				imagefilledrectangle($image, $x_legend_extend - 4, $y_legend_extend + 2, $x_legend_extend + $width_legend - 2, $y_legend_extend + $height_legend + 4, $shadow);
				imagefilledrectangle($image, $x_legend_extend - 1, $y_legend_extend - 1, $x_legend_extend + $width_legend + 1, $y_legend_extend + $height_legend + 1, $black);
				imagefilledrectangle($image, $x_legend_extend, $y_legend_extend, $x_legend_extend + $width_legend, $y_legend_extend + $height_legend, $white);
				
				$this->color_index = 0;
				$angle = 0;
				$i = 0;
				foreach($this->data_stats as $name_value => $angle_value)
				{					
					$get_color = $this->array_allocated_color[$this->imagecolorallocatedark(false, NO_ALLOCATE_COLOR)];
					if( $i < 8 )
					{					
						//Carré de couleur.
						imagefilledrectangle($image, $x_legend_extend + 6, $y_legend_extend + (16*$i) + 7, $x_legend_extend + 18, $y_legend_extend + (16*$i) + 19, $black);
						imagefilledrectangle($image, $x_legend_extend + 7, $y_legend_extend + (16*$i) + 8, $x_legend_extend + 17, $y_legend_extend + (16*$i) + 18, $get_color);
						
						//Texte
						$text = ucfirst(substr($name_value, 0, 14)) . ' (' . (($angle_value != 360) ? $this->number_round(($angle_value/3.6), 1) . '%' : '100%') . ')';
						$textcolor = imagecolorallocate($image, 0, 0, 0);
						
						imagettftext($image, $font_size, 0, $x_legend_extend + 24, $y_legend_extend + (16*$i) + 17, $black, $font, $text);
						$i++;
					}
					else
						break;
					$angle += $angle_value;
				}
			}
			
			//Affichage de l'image
			header('Content-type: image/png');				
			if( !empty($img_cache) )
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
	
	//Création de l'image d'erreur
	function create_pics_error($width, $height, $font_size, $font)
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
		@imagettftext($thumbtail, $font_size, 0, $text_x, $text_y, $text_color, $font, 'Error Image');
		
		//Affichage de l'image
		header('Content-type: image/png');
		imagepng($thumbtail);
		imagedestroy($thumbtail);
	}
	
	//$this->number_round nbr au nbr de décimal voulu
	function number_round($nombre, $dec)
	{
		return trim(number_format($nombre, $dec, '.', ''));
	}
}

?>