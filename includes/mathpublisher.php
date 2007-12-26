<?php
/***************************************************************************
 *   copyright            : (C) 2005 by Pascal Brachet - France            *
 *   pbrachet_NOSPAM_xm1math.net (replace _NOSPAM_ by @)                   *
 *   http://www.xm1math.net/phpmathpublisher/                              *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

/********* HOW TO USE PHPMATHPUBLISHER ****************************
1) Fix the path to the fonts and the images directory (see PARAMETERS TO MODIFY below)
2) Include this script in your php page :
include("mathpublisher.php") ;
3) Just call the mathfilter($text,$size,$pathtoimg) function in your php page.
$text is the text with standard html tags and mathematical expressions (defined by the <m>...</m> tag).
$size is the size of the police used for the formulas.
$pathtoimg is the relative path between the html pages and the images directory.
With a simple "echo mathfilter($text,$size,$pathtoimg);", you can display text with mathematical formulas.
The mathfilter function will replace all the math tags (<m>formula</m>) in $text by <img src=the formula image >.
Example : 
mathfilter("A math formula : <m>f(x)=sqrt{x}</m>,12,"img/") will return :
"A math formula : <img src=\"img/math_988.5_903b2b36fc716cfb87ff76a65911a6f0.png\" style=\"vertical-align:-11.5px; display: inline-block ;\" alt=\"f(x)=sqrt{x}\" title=\"f(x)=sqrt{x}\">"
The image corresponding to a formula is created only once. Then the image is stocked into the image directories.
The first time that mathfilter is called, the images corresponding to the formulas are created, but the next times mathfilter will only return the html code.

NOTE : if the free latex fonts furnished with this script don't work well (very tiny formulas - that's could happened with some GD configurations), you should try to use the bakoma versions of these fonts (downloadable here : http://www.ctan.org/tex-archive/fonts/cm/ps-type1/bakoma/ttf/ )
*******************************************************************/

//********* PARAMETERS TO MODIFY *********************************
// The four global variables. Uncomment the line if you need it.
//global DIR_FONT,DIR_IMG,$symboles,$fontesmath;

// choose the type of the declaration according to your server settings (some servers don't accept the dirname(__FILE__) command for security reasons).

// NEW in 0.3 version : no more / at the end of DIR_FONT and DIR_IMG

//path to the images directory
define('DIR_IMG', '../images/maths');
// or DIR_IMG=dirname(__FILE__)."/phpmathpublisher/img";
// path to the fonts directory
define('DIR_FONT', '../includes/data/fonts');
// or DIR_FONT=dirname(__FILE__)."/phpmathpublisher/fonts";


function detectimg($n)
{
	/*
	Detects if the formula image already exists in the DIR_IMG cache directory. 
	In that case, the function returns a parameter (recorded in the name of the image file) which allows to align correctly the image with the text.
	*/
	$ret = 0;
	$handle = opendir(DIR_IMG);
	while( !is_bool($fi = readdir($handle)) )
	{
		if( strpos($fi, $n) !== false ) 
		{
			$v = explode('_', $fi);
			$ret = $v[1];
			break;
		}
	}
	closedir($handle);
	
	return $ret;
}


function mathimage($text, $size, $pathtoimg)
{
	//Creates the formula image (if the image is not in the cache) and returns the <img src=...></img> html code. 
	$nameimg = md5(trim($text) . $size) . '.png';
	$v = detectimg($nameimg);
	if( $v == 0 )
	{
		//the image doesn't exist in the cache directory. we create it.		
		include_once('../includes/mathpublisher.class.php');
		
		$formula = new expression_math(tableau_expression(trim($text)));
		$formula->dessine($size);
		$v = 1000 - imagesy($formula->image) + $formula->base_verticale + 3;
		//1000+baseline ($v) is recorded in the name of the image
		ImagePNG($formula->image, DIR_IMG . '/math_' . $v . '_' . $nameimg);
	}
	$valign = $v - 1000;
	
	//On passe à la moulinette le texte alternatif pour éviter des failles xss.
	$text = htmlentities(strip_tags($text));
	
	return '<img src="'. DIR_IMG . '/math_' . $v . '_' . $nameimg . '" style="vertical-align:' . $valign . 'px;display: inline-block;background-color: #FFFFFF;" alt="' . $text . '" title="' . $text . '"/>';
}


function mathfilter($text, $size, $pathtoimg = '../images/maths/') 
{
	/* THE MAIN FUNCTION
	1) the content of the math tags (<m></m>) are extracted in the $t variable (you can replace <m></m> by your own tag).
	2) the "mathimage" function replaces the $t code by <img src=...></img> according to this method :
	- if the image corresponding to the formula doesn't exist in the DIR_IMG cache directory (detectimg($nameimg)=0), the script creates the image and returns the "<img src=...></img>" code.
	- otherwise, the script returns only the <img src=...></img>" code.
	To align correctly the formula image with the text, the "valign" parameter of the image is required.
	That's why a parameter (1000+valign) is recorded in the name of the image file (the "detectimg" function returns this parameter if the image exists in the cache directory)
	To be sure that the name of the image file is unique and to allow the script to retrieve the valign parameter without re-creating the image, the syntax of the image filename is :
	math_(1000+valign)_md5(formulatext.size).png.
	(1000+valign is used instead of valign directly to avoid a negative number)
	*/
	$text = stripslashes($text);
	$size = max($size, 10);
	$size = min($size, 24);
	
	return mathimage(trim($text), $size, $pathtoimg);	
}

?>