<?php
/*##################################################
 *                        ArticlesKeywordsService.class.php
 *                            -------------------
 *   begin                : March 05, 2013
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

class ArticlesKeywordsService
{
        private static $db_querier;
        
        public static function __static()
        {
                self::$db_querier = PersistenceContext::get_querier();
        }
        
        public static function add(ArticlesKeywords $keyword, $id_article)
	{
		$result = self::$db_querier->insert(ArticlesSetup::$articles_keywords_table, $keyword->get_properties());
                $this->add_relation($result->get_last_inserted_id(), $id_article);
	}
	
        private static function add_relation($id_keyword, $id_article)
        {
                self::$db_querier->insert(ArticlesSetup::$articles_keywords_relation_table, array(
                    'id_article' => $id_article,
                    'id_keyword' => $id_keyword
                ));
        }
        
        public static function delete($id_article)
	{
                $keywords = $this->get_keywords($id_article);
                
                foreach($keywords as $keyword)
                {
                    self::$db_querier->delete(ArticlesSetup::$articles_keywords_table, 'WHERE id=:id_keyword', array(
                        'id_keyword' => $keyword['id']
                    ));
                }
            
                self::$db_querier->delete(ArticlesSetup::$articles_keywords_relation_table, 'WHERE id_article=:id_article',array(
                    'id_article' => $id_article
                ));
	}
        
        public static function get_keywords($id_article)
	{
		$query = 'SELECT k.id, k.name, k.rewrited_name FROM '. ArticlesSetup::$articles_keywords_table.'k INNER JOIN '.
                         ArticlesSetup::$articles_keywords_relation_table.'r ON k.id = r.id_keyword WHERE r.id_article=:id_article';
                $parameters = array('id_article' => $id_article);
                $rows = self::$db_querier->select_rows($query, $parameters, SelectQueryResult::FETCH_ASSOC);
               
                return $rows;
	}
}
?>