<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 18
 * @since       PHPBoost 4.0 - 2014 11 27
*/

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

		FaqService::delete($this->faq_question->get_id());

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
