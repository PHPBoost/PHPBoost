<?php
/*##################################################
 *                               faq_mini.php
 *                            -------------------
 *   begin                : June 21, 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if( defined('PHPBOOST') !== true ) exit;

load_module_lang('faq');
$Cache->load('faq'); //Chargement du cache
include_once(PATH_TO_ROOT . '/faq/faq_begin.php');
include_once(PATH_TO_ROOT . '/faq/faq_cats.class.php');

###########################Affichage##############################
$Template->set_filenames(array(
	'faq_mini'=> 'faq/faq_mini.tpl'
));

$random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];

$faq_cats = new FaqCats();

$i = 0;

while( !$faq_cats->check_auth($random_question['idcat']) && $i < 5 )
{
	$random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];
	$i++;
}

if( $i < 5 && !empty($random_question['question']) )
	$Template->assign_vars(array(
		'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
		'FAQ_QUESTION' => $random_question['question'],
		'U_FAQ_QUESTION' => PATH_TO_ROOT . '/faq/' . ($random_question['idcat'] > 0 ? transid('faq.php?id=' . $random_question['idcat'] . '&amp;question=' . $random_question['id'], 'faq-' . $random_question['idcat'] . '+' . url_encode_rewrite($FAQ_CATS[$random_question['idcat']]['name']) . '.php?question=' . $random_question['id']) . '#q' . $random_question['id'] : transid('faq.php?question=' . $random_question['id'], 'faq.php?question=' . $random_question['id']) . '#q' . $random_question['id'])
	));
else
	$Template->assign_vars(array(
		'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
		'FAQ_QUESTION' => $FAQ_LANG['no_random_question'],
		'U_FAQ_QUESTION' => PATH_TO_ROOT . '/faq/' . transid('faq.php')
	));


?>