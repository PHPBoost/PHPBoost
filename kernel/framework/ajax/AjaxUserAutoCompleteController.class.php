<?php
class AjaxUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$tpl = new StringTemplate('<ul> # START results # <li>{results.NAME}</li> # END results # </ul>');
 
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '" . $request->get_value('value', '') . "%'",
			array(), SelectQueryResult::FETCH_ASSOC);
 
		while($row = $result->fetch())
		{
			$tpl->assign_block_vars('results', array('NAME' => $row['login']));
		}
 
		return new SiteNodisplayResponse($tpl);
	}
}
?>