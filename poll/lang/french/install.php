<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 01 02
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                       French                      #
#####################################################

$lang['categories'] = $lang['items'] = array();

$lang['categories'][] = array(
	'category.name'        => 'Catégorie de test',
	'category.description' => 'Elements basiques de démonstration'
);

$lang['items'][] = array(
	'item.title'   => 'Critique du site',
	'item.additional_fields.question' => 'Comment trouvez-vous notre site ?',
	'item.additional_fields.answers_type' => 1,
	'item.additional_fields.answers' => TextHelper::serialize(array(
		'Supersite'    => array('is_default' => false, 'title' => 'Super site'),
		'Pasmal'       => array('is_default' => false, 'title' => 'Pas mal'),
		'Plutôtmoyen'  => array('is_default' => false, 'title' => 'Plutôt moyen'),
		'Bof'          => array('is_default' => false, 'title' => 'Bof')
	)),
	'item.additional_fields.votes' => TextHelper::serialize(array('Super site' => 15, 'Pas mal' => 3, 'Plutôt moyen' => 6, 'Bof' => 0)),
	'item.additional_fields.votes_number' => 24,
	'item.additional_fields.close_poll' => 0,
	'item.additional_fields.countdown_display' => 0,

	'item.content' => '',
	'item.summary' => ''
);
?>
