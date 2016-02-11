<?php
/*##################################################
 *                          FaqAjaxDeleteQuestionController.class.php
 *                            -------------------
 *   begin                : November 27, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class FaqAjaxDeleteQuestionController extends AbstractController
{
	private $faq_question;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->get_faq_question($request);
		
		if ($this->faq_question !== null && $this->check_authorizations())
		{
			$this->delete_question();
			$code = $this->faq_question->get_id();
		}
		else
			$code = -1;
		
		return new JSONResponse(array('code' => $code, 'questions_number' => ($code > 0 ? FaqService::count() : 0)));
	}
	
	private function delete_question()
	{
		AppContext::get_session()->csrf_post_protect();
		
		FaqService::delete('WHERE id=:id', array('id' => $this->faq_question->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'faq', 'id' => $this->faq_question->get_id()));
		
		Feed::clear_cache('faq');
		FaqCache::invalidate();
		FaqCategoriesCache::invalidate();
	}
	
	private function get_faq_question(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		if (!empty($id))
		{
			try {
				$this->faq_question = FaqService::get_question('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
			}
		}
	}
	
	private function check_authorizations()
	{
		if (!$this->faq_question->is_authorized_to_delete() || AppContext::get_current_user()->is_readonly())
			return false;
		else
			return true;
	}
}
?>
