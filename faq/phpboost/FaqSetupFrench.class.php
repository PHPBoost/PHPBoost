<?php
/*##################################################
 *                           FaqSetupFrench.class.php
 *                            -------------------
 *   begin                : January 16, 2009
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class FaqSetupFrench extends FaqSetup
{
	protected function insert_faq_data()
	{
		$common_query = AppContext::get_sql_common_query();
		$common_query->insert(self::$faq_table, array(
			'id' => 1,
			'idcat' => 2,
			'q_order' => 1,
			'question' => 'Qu\'est ce qu\'un CMS?',
			'answer' => 'C\'est un système de gestion de contenu ou SGC en français (en anglais :  Content Management Systems)',
			'user_id' => 1,
			'timestamp' => 1242496334
		));
		$common_query->insert(self::$faq_table, array(
			'id' => 2,
			'idcat' => 1,
			'q_order' => 1,
			'question' => 'Qu\'est-ce que PHPBoost ?',
			'answer' => 'PHPBoost est un CMS (Content Management System ou système de gestion de contenu) français. Ce logiciel permet à n\'importe qui de créer son site de façon très simple, tout est assisté. Conçu pour satisfaire les débutants, il devrait aussi ravir les utilisateurs expérimentés qui souhaiteraient pousser son fonctionnement ou encore développer leurs propres modules.',
			'user_id' => 1,
			'timestamp' => 1242496518
		));
	}

	protected function insert_faq_cats_data()
	{
		$common_query = AppContext::get_sql_common_query();
		$common_query->insert(self::$faq_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => null,
			'name' => 'PHPBoost',
			'visible' => 1,
			'display_mode' => 0,
			'description' => 'Des questions sur PHPBoost?',
			'image' => 'faq.png',
			'num_questions' => 1
		));
		$common_query->insert(self::$faq_cats_table, array(
			'id' => 2,
			'id_parent' => 0,
			'c_order' => 2,
			'auth' => null,
			'name' => 'Dictionnaire',
			'visible' => 1,
			'display_mode' => 0,
			'description' => '',
			'image' => 'faq.png',
			'num_questions' => 1
		));
	}
}

?>
