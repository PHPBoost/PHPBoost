<?php
/*##################################################
 *                               faq_mini.php
 *                            -------------------
 *   begin                : June 21, 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

if (defined('PHPBOOST') !== true) exit;

function faq_mini($position, $block)
{
    global $Cache, $Template, $FAQ_LANG, $FAQ_CATS, $RANDOM_QUESTIONS;
    
    load_module_lang('faq');
    $Cache->load('faq'); //Chargement du cache
    
    include_once(PATH_TO_ROOT . '/faq/faq_begin.php');
    
    $tpl = new Template('faq/faq_mini.tpl');
    
    MenuService::assign_positions_conditions($tpl, $block);
    
    $no_random_question = array(
    	'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
    	'FAQ_QUESTION' => $FAQ_LANG['no_random_question'],
    	'U_FAQ_QUESTION' => TPL_PATH_TO_ROOT . '/faq/' . url('faq.php')
    );
    
    //Aucune question à afficher
    if (empty($RANDOM_QUESTIONS))
    {
    	$tpl->assign_vars($no_random_question);
    	return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
    }
    
    $random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];
    
    $faq_cats = new FaqCats();
    
    $i = 0;
    
    //Tant que la question tirée aléatoirement n'est pas lisible par le visiteur, on en choisit une
    //On met un "timeout" de 5 essais pour ne pas perdre trop de temps
    while (!$faq_cats->check_auth($random_question['idcat']) && $i < 5)
    {
    	$random_question = $RANDOM_QUESTIONS[array_rand($RANDOM_QUESTIONS)];
    	$i++;
    }
    
    //Question trouvée avant 5 essais
    if ($i < 5 && !empty($random_question['question']))
    {
    	$tpl->assign_vars(array(
    		'L_FAQ_RANDOM_QUESTION' => $FAQ_LANG['random_question'],
    		'FAQ_QUESTION' => $random_question['question'],
    		'U_FAQ_QUESTION' => PATH_TO_ROOT . '/faq/' . ($random_question['idcat'] > 0 ? url('faq.php?id=' . $random_question['idcat'] . '&amp;question=' . $random_question['id'], 'faq-' . $random_question['idcat'] . '+' . Url::encode_rewrite($FAQ_CATS[$random_question['idcat']]['name']) . '.php?question=' . $random_question['id']) . '#q' . $random_question['id'] : url('faq.php?question=' . $random_question['id'], 'faq.php?question=' . $random_question['id']) . '#q' . $random_question['id'])
    	));
    }
    //Echec
    else
    {
    	$tpl->assign_vars($no_random_question);
    }
    //On retourne le contenu du bloc
    return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
}
?>