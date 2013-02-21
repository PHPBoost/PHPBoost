<?php
class NewsDisplayPendingNewsController extends ModuleController
{
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->tpl = new StringTemplate('');
	}
	
	public function build_view()
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.level, member.user_groups
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		WHERE news.approbation_type = 0 OR (news.approbation_type = 2 AND (news.start_date > :timestamp_now OR end_date < :timestamp_now))', array(
			'timestamp_now' => $now->get_timestamp()
		));

		while ($row = $result->fetch())
		{

		}
	}
		
	private function generate_response()
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->lang['news.pending']);
		
		$response->add_breadcrumb_link($this->lang['news'], NewsUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['news.pending'], NewsUrlBuilder::display_pending_news());
	
		return $response->display($this->tpl);
	}
}
?>