<?php
/*##################################################
 *                           mysql_dao_builder.class.php
 *                            -------------------
 *   begin                : June 13 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('mvc/dao/builder/sql_dao_builder');

class MySQLDAOBuilder extends SQLDAOBuilder
{
    public function __construct($model, $cache_path = SQLDAOBuilder::cache_path)
    {
        parent::__construct($model, $cache_path);
    }

    protected function cache_classname()
    {
        return $this->model->name() . 'MySQLDAO';	
    }
    
    protected function get_template_filename()
    {
    	return self::$template_filename;
    }
    protected function generate_content()
    {
    	$tpl = parent::generate_content($this->get_template_filename());
    	$clauses = $this->model->joins();
    	$join_clause = array();
    	foreach ($clauses as $left_key => $right_field)
    	{
    	   $join_clause[] = $left_key . '=' . $right_field;
    	}
    	$tpl->assign_vars(array(
            'TABLES_NAMES' => implode(',', $this->model->used_tables()),
            'JOIN_CLAUSE' => implode(' AND ', $join_clause)
    	));
    	return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    private static $template_filename = 'mysql_dao_builder';
}
?>