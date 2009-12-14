<?php
/***************************************************************************
 *   copyright            : (C) 2005 by Pascal Brachet - France            *
 *   pbrachet_NOSPAM_xm1math.net (replace _NOSPAM_ by @)                   *
 *   http://www.xm1math.net/phpmathpublisher/                              *
 *                                                                         *
 *  This program is free software; you can redistribute it and/or modify  *
 *  it under the terms of the GNU General Public License as published by  *
 *  the Free Software Foundation; either version 2 of the License, or     *
 *  (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

define('DIR_IMG', PATH_TO_ROOT . '/images/maths'); //path to the images directory
define('DIR_FONT', PATH_TO_ROOT . '/kernel/data/fonts'); // path to the fonts directory

function detectimg($n)
{
	//Detects if the formula image already exists in the DIR_IMG cache directory. In that case, the function returns a parameter (recorded in the name of the image file) which allows to align correctly the image with the text.
	$ret = 0;
	$handle = opendir(DIR_IMG);
	while (!is_bool($fi = readdir($handle)))
	{
		if (strpos($fi, $n) !== false) 
		{
			$v = explode('_', $fi);
			$ret = $v[1];
			break;
		}
	}
	closedir($handle);
	
	return $ret;
}

function mathimage($text, $size)
{
	//Creates the formula image (if the image is not in the cache) and returns the <img src=...></img> html code. 
	$nameimg = md5(trim($text) . $size) . '.png';
	$v = detectimg($nameimg);
	if ($v == 0)
	{
		//the image doesn't exist in the cache directory. we create it.		
		global $symboles, $fontesmath;
		
		
		$formula = new expression_math(tableau_expression(trim($text)));
		$formula->dessine($size);
		$v = 1000 - imagesy($formula->image) + $formula->base_verticale + 3; //1000+baseline ($v) is recorded in the name of the image
		imagepng($formula->image, DIR_IMG . '/math_' . $v . '_' . $nameimg);
	}
	$valign = $v - 1000;
	
	//On passe à la moulinette le texte alternatif pour éviter des failles xss.
	$text = htmlentities(strip_tags($text));
	
	return '<img src="/images/maths/math_' . $v . '_' . $nameimg . '" style="vertical-align:' . $valign . 'px;display:inline-block;background-color:#FFFFFF;" alt="' . $text . '" title="' . $text . '"/>';
}

function mathfilter($text, $size) 
{
	$text = stripslashes($text);
	$size = max($size, 10);
	$size = min($size, 24);
	
	return mathimage(trim($text), $size);	
}

?>