<?php
/*##################################################
 *                     FaqHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 06, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class FaqHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $FAQ_LANG;
		
		return $FAQ_LANG['title_faq'];
	}
	
	private function get_view()
	{
		global $idartcat, $Session, $User, $invisible, $Cache, $Bread_crumb, $ARTICLES_CAT, $CONFIG_ARTICLES, $LANG, $ARTICLES_LANG;
		
		require_once('../faq/faq_begin.php');
		
		$tpl = new FileTemplate('faq/faq.tpl');

		$tpl->put_all(array(
			'TITLE' => $TITLE,
		));

		if (!empty($FAQ_CATS[$id_faq]['description']) && $id_faq > 0)
		{
			$tpl->assign_block_vars('description', array(
				'DESCRIPTION' => FormatingHelper::second_parse($FAQ_CATS[$id_faq]['description'])
			));
		}
		else
		{
			$tpl->assign_block_vars('description', array(
				'DESCRIPTION' => FormatingHelper::second_parse($faq_config->get_root_cat_description())
			));
		}

		if ($auth_write)
			$tpl->assign_block_vars('management', array());

		//let's check if there are some subcategories
		$num_subcats = 0;
		foreach ($FAQ_CATS as $id => $value)
		{
			if ($id != 0 && $value['id_parent'] == $id_faq)
				$num_subcats ++;
		}

		//listing of subcategories
		if ($num_subcats > 0)
		{	
			$tpl->put_all(array(
				'C_FAQ_CATS' => true
			));	
			
			$i = 1;
			foreach ($FAQ_CATS as $id => $value)
			{
				//List of children categories
				if ($id != 0 && $value['visible'] && $value['id_parent'] == $id_faq && (empty($value['auth']) || $User->check_auth($value['auth'], AUTH_READ)))
				{
					if ( $i % $faq_config->get_number_columns() == 1 )
						$tpl->assign_block_vars('row', array());
					$tpl->assign_block_vars('row.list_cats', array(
						'ID' => $id,
						'NAME' => $value['name'],
						'WIDTH' => floor(100 / (float)$faq_config->get_number_columns()),
						'SRC' => $value['image'],
						'IMG_NAME' => addslashes($value['name']),
						'NUM_QUESTIONS' => sprintf(((int)$value['num_questions'] > 1 ? $FAQ_LANG['num_questions_plural'] : $FAQ_LANG['num_questions_singular']), (int)$value['num_questions']),
						'U_CAT' => FaqUrlBuilder::get_link_cat($id,$value['name']),
						'U_ADMIN_CAT' => url('admin_faq_cats.php?edit=' . $id)
					));
					
					if (!empty($value['image']))
						$tpl->put_all(array(
							'C_CAT_IMG' => true
						));
						
					$i++;
				}
			}
		}

		//Displaying the questions that this cat contains
		$result = $Sql->query_while("SELECT id, question, q_order, answer
		FROM " . PREFIX . "faq
		WHERE idcat = '" . $id_faq . "'
		ORDER BY q_order",
		__LINE__, __FILE__);

		$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "faq_cats WHERE idcat = '" . $id_faq . "'", __LINE__, __FILE__);

		if ($num_rows > 0)
		{
			//Display mode : if this category has a particular display mode we use it, else we use default display mode. If the category is the root we use default mode.
			if ($id_faq > 0)
			{
				$faq_display_block = ($FAQ_CATS[$id_faq]['display_mode'] > 0) ? ($FAQ_CATS[$id_faq]['display_mode'] == 2 ? true : false ) : ($faq_config->get_display_mode() == 'inline' ? false : true);
			}
			else
			{
				$faq_display_block = ($faq_config->get_root_cat_display_mode() == 0) ? ($faq_config->get_display_mode() == 'inline' ? false : true) : ($faq_config->get_root_cat_display_mode() == 1 ? false : true);
			}
			//Displaying administration tools
			$tpl->put_all(array(
				'C_ADMIN_TOOLS' => $auth_write
			));
			
			if (!$faq_display_block)
				$tpl->assign_block_vars('questions', array());
			else
				$tpl->assign_block_vars('questions_block', array());
				
			while ($row = $Sql->fetch_assoc($result))
			{
				if (!$faq_display_block)
				{
					$tpl->assign_block_vars('questions.faq', array(
						'ID_QUESTION' => $row['id'],
						'QUESTION' => $row['question'],
						'ANSWER' => FormatingHelper::second_parse($row['answer']),
						'U_QUESTION' => FaqUrlBuilder::get_link_question($id_faq,$row['id']),
						'U_DEL' => url('action.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
						'U_DOWN' => url('action.php?down=' . $row['id']),
						'U_UP' => url('action.php?up=' . $row['id']),
						'U_MOVE' => url('management.php?move=' . $row['id']),
						'U_EDIT' => url('management.php?edit=' . $row['id']),
						'C_HIDE_ANSWER' => $row['id'] != $id_question,
						'C_SHOW_ANSWER' => $row['id'] == $id_question
					));
					if ($row['q_order'] > 1)
						$tpl->assign_block_vars('questions.faq.up', array());
					if ($row['q_order'] < $num_rows)
						$tpl->assign_block_vars('questions.faq.down', array());
				}
				else
				{
					$tpl->assign_block_vars('questions_block.header', array(
						'QUESTION' => $row['question'],
						'ID' => $row['id']
					));
					$tpl->assign_block_vars('questions_block.contents', array(
						'ANSWER' => FormatingHelper::second_parse($row['answer']),
						'QUESTION' => $row['question'],
						'ID' => $row['id'],
						'U_DEL' => url('action.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
						'U_DOWN' => url('action.php?down=' . $row['id']),
						'U_UP' => url('action.php?up=' . $row['id']),
						'U_EDIT' => url('management.php?edit=' . $row['id']),
						'U_MOVE' => url('management.php?move=' . $row['id']),
						'U_QUESTION' => FaqUrlBuilder::get_link_question($id_faq,$row['id'])
					));
					
					if ($row['q_order'] > 1)
						$tpl->assign_block_vars('questions_block.contents.up', array());
					if ($row['q_order'] < $num_rows)
						$tpl->assign_block_vars('questions_block.contents.down', array());
				}
			}
		}
		else
		{
			$tpl->assign_block_vars('no_question', array());
		}

		$tpl->put_all(array(
			'L_NO_QUESTION_THIS_CATEGORY' => $FAQ_LANG['faq_no_question_here'],
			'L_CAT_MANAGEMENT' => $FAQ_LANG['category_manage'],
			'L_EDIT' => $FAQ_LANG['update'],
			'L_DELETE' => $FAQ_LANG['delete'],
			'L_UP' => $FAQ_LANG['up'],
			'L_DOWN' => $FAQ_LANG['down'],
			'L_MOVE' => $FAQ_LANG['move'],
			'L_CONFIRM_DELETE' => $FAQ_LANG['confirm_delete'],
			'L_QUESTION_URL' => 'URL de la question',
			'LANG' => get_ulang(),
			'THEME' => get_utheme(),
			'C_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
			'U_MANAGEMENT' => url('management.php?faq=' . $id_faq),
			'U_ADMIN_CAT' => $id_faq > 0 ? url('admin_faq_cats.php?edit=' . $id_faq) : url('admin_faq_cats.php')
		));

		return $tpl;
	}
}
?>