<?php
/*##################################################
 *                      ArticlesModuleHomePage.class.php
 *                            -------------------
 *   begin                : March 19, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class ArticlesModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$request = AppContext::get_request();
		$this->init();
		
		$current_page = $request->get_int('page', 1);
		$nbr_categories = PersistenceContext::get_querier();
		$nbr_pages =  ceil();
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern();
		
                $this->view->put_all(array(
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = (($limit_page - 1) * );
		
		$result = PersistenceContext::get_querier()->select('',
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
                
		while ($row = $result->fetch())
		{
			
                        $this->view->assign_block_vars('streams_list', array(
                                
                        ));
			
		}
		
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>