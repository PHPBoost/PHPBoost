<?php
/*##################################################
 *                               verif_code.php
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

define('NO_SESSION_LOCATION', true); //Ne réactualise pas l'emplacement du visiteur/membre
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');
header('Content-type: image/jpeg'); //Envoi du header.

//Configuration
$width = 160;
$height = 50;
$font = '../kernel/data/fonts/impact.ttf';
$rand = rand(0,1);

##Génération du code##
$words = array('code', 'goal', 'image', 'php', 'boost', 'query', 'word', 'prog', 'azerty', 'popup', 'exit', 'verif', 'genre', 'order', 'bots', 'search', 'design', 'exec', 'web', 'color', 'lunar', 'inter', 'extern', 'cache', 'media', 'video', 'cms', 'cesar', 'watt', 'auto', 'audio', 'data', 'dico', 'site', 'mail', 'email', 'spam', 'bot', 'bots', 'index', 'rand', 'text');
$code = str_shuffle($words[array_rand($words)] . substr(rand(0, 99), 0, rand(1, 2)));
$code = strtr($code, '1lo0', '    ');

##Création de l'image##
if( !function_exists('imagecreatetruecolor') )
	$img = @imagecreate($width, $height);
else
	$img = @imagecreatetruecolor($width, $height);

##Définition des couleurs##
$array_color = array(array(224, 118, 27), array(48, 149, 53), array(254, 249, 52), array(102, 133, 237), array(204, 42, 38), array(53, 144, 189), array(102, 102, 153), array(236, 230, 208), array(213, 171, 1), array(182, 0, 51), array(193, 73, 0), array(25, 119, 128), array(182, 181, 177), array(102, 133, 237));

//Choix aléatoire de couleur, et suppression du tableau pour éviter une réutilisation pour le texte.
$bg_bis_index_color = array_rand($array_color);	
list($r, $g, $b) = $array_color[$bg_bis_index_color];
$bg_img = @imagecolorallocate($img, $r, $g, $b);
unset($array_color[$bg_bis_index_color]);

$bg_index_color = array_rand($array_color);	
list($r, $g, $b) = $array_color[$bg_index_color];
$bg = @imagecolorallocate($img, $r, $g, $b);
unset($array_color[$bg_index_color]);

$bg_bis_index_color = array_rand($array_color);	
list($r, $g, $b) = $array_color[$bg_bis_index_color];
$bg_bis = @imagecolorallocate($img, $r, $g, $b);
unset($array_color[$bg_bis_index_color]);

$black = @imagecolorallocate($img, 0, 0, 0);

//Calcul de la version sombre de la couleur. Paramètres couleur de l'effet 3D, mask_color: 0 pour sombre, 255 pour lumineux; similar_color: entre 0.40 (très différents et 0.99 très proche.
function imagecolorallocatedark($array_color, $mask_color = 0, $similar_color = 0.40)
{
	list($r, $g, $b) = $array_color;
	$rd = round($r * $similar_color) + round($mask_color * (1 - $similar_color));
	$gd = round($g * $similar_color) + round($mask_color * (1 - $similar_color));
	$bd = round($b * $similar_color) + round($mask_color * (1 - $similar_color));

	return array($rd, $gd, $bd);
}
	
##Création de l'arrère plan
//Couleur de fond
@imagefilledrectangle($img, 0, 0, $width, $height, $bg_img);
//Brouillage de l'image.
$style = array($bg, $bg, $bg, $bg, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis, $bg_bis);
@imagesetstyle($img, $style);

if( $rand )
	for($i = 0; $i <= $height; $i = ($i + 2))
		@imageline($img, 0, $i, $width, $i, IMG_COLOR_STYLED);
else
	for($i = $height; $i >= 0; $i = ($i - 2))
		@imageline($img, 0, $i, $width, $i, IMG_COLOR_STYLED);

##Attribut du code à écrire##	
//Centrage du texte.	
$global_font_size = 24;
$array_size_ttf = @imagettfbbox($global_font_size + 2, 0, $font, $code);
$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
$text_x = ($width/2) - ($text_width/2);
$text_y = ($height/2) + ($text_height/2);

preg_match_all('/.{1}/s', $code, $matches);
foreach($matches[0] as $key => $letter)
{	
	//Allocation des couleurs.
	$index_color = array_rand($array_color);
	list($r, $g, $b) = $array_color[$index_color];
	$text_color = @imagecolorallocate($img, $r, $g, $b);
	list($r, $g, $b) = imagecolorallocatedark($array_color[$index_color]);
	$text_color_dark = @imagecolorallocate($img, $r, $g, $b);
	
	$font_size = rand($global_font_size - 4, $global_font_size);
	$angle = rand(-15, 15);
	$move_y = $text_y + rand(-15, 4);
	
	//Ajout de l'ombre.
	@imagettftext($img, $font_size, $angle, ($text_x + 1), ($move_y + 1), $text_color_dark, $font, $letter);	
	
	//Ecriture du code.
	@imagettftext($img, $font_size, $angle, $text_x, $move_y, $text_color, $font, $letter);
	$array_size_ttf = @imagettfbbox($font_size, $angle, $font, $code);
	$text_width = max(abs($array_size_ttf[2] - $array_size_ttf[0]), 5);
	$text_x += $global_font_size - 6;
}

//Bordure
@imagerectangle($img, 0, 0, $width - 1, $height - 1, $black);

##Envoi de l'image##
imagejpeg($img);
imagedestroy($img);

$user_id = substr(strhash(USER_IP), 0, 8);
$check_user_id = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."verif_code WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
if( $check_user_id == 1 )
	$Sql->Query_inject("UPDATE ".PREFIX."verif_code SET code = '" . $code . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
else
{
	$Sql->Query_inject("INSERT INTO ".PREFIX."verif_code (user_id, code, timestamp) VALUES ('" . $user_id . "', '" . $code . "', '" . time() . "')", __LINE__, __FILE__);
}

require_once('../kernel/footer_no_display.php');
?>