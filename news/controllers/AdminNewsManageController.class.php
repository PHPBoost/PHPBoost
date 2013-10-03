<?php
/*##################################################
 *                      AdminNewsManageController.class.php
 *                            -------------------
 *   begin                : June 24, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminNewsManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	private static $number_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form($request);

		return new AdminNewsDisplayResponse($this->view, $this->lang['news.manage']);
	}

	private function build_form($request)
	{
		$pagination = $this->get_pagination();
		
		$this->view->put_all(array(
			'C_NEWS_EXISTS' => !$pagination->current_page_is_empty(),
			'PAGINATION' => $pagination->display()
		));
		
		$result = PersistenceContext::get_querier()->select('SELECT news.*, cat.name AS category_name, member.login
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. NewsSetup::$news_cats_table .' cat ON cat.id = news.id_category
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		ORDER BY news.creation_date DESC, news.approbation_type DESC
		LIMIT :number_per_page OFFSET :start_limit',
			array(
				'number_per_page' => self::$number_per_page,
				'start_limit' => $pagination->get_display_from()
		));
		while ($row = $result->fetch())
		{
			switch ($row['approbation_type']) {
				case News::APPROVAL_NOW:
					$status = $this->lang['news.form.approved.now'];
				break;
				case News::APPROVAL_DATE:
					$status = $this->lang['news.form.approved.date'];
				break;
				case News::NOT_APPROVAL:
					$status = $this->lang['news.form.approved.not'];
				break;
			}
			
			$date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['creation_date']);
			$this->view->assign_block_vars('news', array(
				'EDIT_LINK' => NewsUrlBuilder::edit_news($row['id'])->absolute(),
				'DELETE_LINK' => NewsUrlBuilder::delete_news($row['id'])->absolute(),
				'NAME' => $row['name'],
				'CATEGORY' => !empty($row['category_name']) ? $row['category_name'] : LangLoader::get_message('root', 'main'),
				'DATE' => $date->format(Date::FORMAT_DAY_MONTH_YEAR, TIMEZONE_AUTO),
				'PSEUDO' => $row['login'],
				'STATUS' => $status
			));
		}
	}
	
	private function get_pagination()
	{
		$number_news = PersistenceContext::get_querier()->count(NewsSetup::$news_table);
		
		$pagination = new ModulePagination(AppContext::get_request()->get_getint('page', 1), $number_news, self::$number_per_page);
		$pagination->set_url(NewsUrlBuilder::manage_news('%d'));
        
		return $pagination;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new FileTemplate('news/AdminNewsManageController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>