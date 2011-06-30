<?php

class SqlParameterExtractorTest extends PHPBoostUnitTestCase
{
	public function test_parse_with_no_string_params()
	{
		$query = "SELECT * FROM TOTO WHERE id=454";
		$this->check_query_and_parameters(
		$query,
		$query,
		array()
		);
	}

	public function test_parse_with_one_simple_string_param()
	{
		$value = 'ceci est un coucou';
		$query = "SELECT * FROM TOTO WHERE toto=%s";
		$extractor = new SqlParameterExtractor(sprintf($query, '\'' .$value . '\''));

		$this->check_query_and_parameters(
		sprintf($query, '\'' .$value . '\''),
		sprintf($query, ':param1'),
		array($value)
		);
	}

	public function test_parse_with_one_salastre_string_param()
	{
		$value = "ceci \" \' est \' \" u\\n coucou";
		$query = "SELECT * FROM TOTO WHERE toto=%s";

		$this->check_query_and_parameters(
		sprintf($query, '\'' .$value . '\''),
		sprintf($query, ':param1'),
		array($value)
		);
	}

	public function test_parse_with_one_big_salastre_string_param()
	{
		$value = "ceci \" \\\\\\' est \" u\\n coucou\\\\";
		$query = "SELECT * FROM TOTO WHERE toto=%s";

		$this->check_query_and_parameters(
		sprintf($query, '\'' .$value . '\''),
		sprintf($query, ':param1'),
		array($value)
		);
	}

	public function test_parse_with_one_very_big_salastre_string_param()
	{
		$value = "Ce bref article va vous don    ner quelques
		conseils simples pour prendre en main ce module.<br />\r\n<br />\r\n<ul class=\"bb_ul\">\r\n
		<li class=\"bb_li\">Pour configurer votre module, <a href=\"/articles/admin_articles_config.php\">
		cliquez ici</a>\r\n       </li><li class=\"bb_li\">Pour ajouter des catégori    es :
		<a href=\"/admin_articles_cat_add.php\">cliquez ici</a> (les catégories et sous catégories
		sont à l''infini)\r\n     </li><li class=\"bb_li\">Pour ajouter un article, vous avez 2 solu
		tions  (les 2 arrivent au même lien)<br />\r\n          <ul class=\"bb_ul\">\r\n
		          <li class=\"bb_li\">Dans la catégorie souhaitée, cliquez sur le bouton ''Ajout''\r\n
		                               </li><li class=\"bb_li\"><a href=\"/articles/admin_artic
		les_add.php\">Cliquez ici</a> pour l''ajouter via le panneau d''administration d
		u module.<br />\r\n             </li></ul>\r\n  </li><li class=\"bb_li\">Pour mett
		re en page vos articles, vous pouvez utiliser le langage bbcode ou l''éditeur WYSIWY
		G (cf cet <a href=\"http://www.phpboost.com/articles/articles-6-61+mise-en-page-du-cont
		enu.php\">article</a>)<br />\r\n</li></ul><br />\r\n<br     />\r\nPour en savoir plu
		s, n''hésitez pas à consulter la documentation du module sur le site de <a href=\"http
		://www.phpboost.com\">PHPBoost</a>.<br />\r\n<br />\r\n<br />\r\nBonne utilisation
		de ce module.";
		$query = "INSERT INTO phpboost_articles (`id`, `idcat`, `title`, `contents`, `icon`, `timestamp`,
		`visible`, `start`, `end`, `user_id`, `views`, `users_note`, `nbrnote`, `note`, `nbr_com`, `lock_com`)
		VALUES (%s);";

		$this->check_query_and_parameters(
		sprintf($query, '\'' .$value . '\''),
		sprintf($query, ':param1'),
		array($value)
		);
	}

	public function test_parse_with_many_salastre_string_params()
	{
		$value1 = "ceci \" \\\\\\' e''st \" u\\n coucou\\\\";
		$value2 = "ceci \" \\\\\\' est \" u\\n coucou\\\\";
		$value3 = "ceci \" \\\\''\\' est \" u\\n coucou\\\\";
		$query = "INSERT INTO phpboost_articles (`id`, ...) VALUES (%s, %s, %s);";

		$this->check_query_and_parameters(
		sprintf($query, '\'' .$value1 . '\'', '\'' .$value2 . '\'', '\'' .$value3 . '\''),
		sprintf($query, ':param1', ':param2', ':param3'),
		array($value1, $value2, $value3)
		);
	}
	
	public function test_parse_with_ending_escaped_quote_character()
	{
		$value1 = 'Comment s\\\'inscrire ?';
		$value2 = 'Allez dans le bloc \\\'se connecter\\\', en bas de ce bloc se trouve un lien \\\'s\\\'inscrire\\\'';
		$query = "INSERT INTO phpboost_faq VALUES (10,8,1,%s,%s,177,1204358106);";
		$this->check_query_and_parameters(
			sprintf($query, '\'' . $value1 . '\'', '\'' . $value2 . '\''),
			sprintf($query, ':param1', ':param2'),
			array($value1, $value2)
		);
	}

	private function check_query_and_parameters($raw_query, $query, $parameters)
	{
		$params = array();
		$i = 0;
		foreach ($parameters as $param)
		{
			$params['param' . ++$i] = str_replace('\'\'', '\'', stripslashes($param));
		}

		$extractor = new SqlParameterExtractor($raw_query);
		self::assertEquals($query, $extractor->get_query());
		self::assertEquals($params, $extractor->get_parameters());
	}
}

?>