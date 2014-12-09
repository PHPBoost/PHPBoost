<?php
/*##################################################
 *                          SandboxHTMLTableModel.class.php
 *                            -------------------
 *   begin                : February 25, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class SandboxHTMLTableModel extends SQLHTMLTableModel
{
	private $parameters;

	public function __construct()
	{
		$columns = array(
			new HTMLTableColumn('pseudo', 'display_name'),
			new HTMLTableColumn('email'),
			new HTMLTableColumn('inscrit le', 'registration_date'),
			new HTMLTableColumn('messages'),
			new HTMLTableColumn('dernière connexion'),
			new HTMLTableColumn('messagerie')
		);
		
		$default_sorting_rule = new HTMLTableSortingRule('user_id', HTMLTableSortingRule::ASC, true);
		
		parent::__construct(DB_TABLE_MEMBER, $columns, $default_sorting_rule);
		
		$this->set_caption('Liste des membres');
		$this->set_id('t42');

		$options = array('horn' => 'Horn', 'coucou' => 'Coucou', 'teston' => 'teston');
		$this->add_filter(new HTMLTableEqualsFromListSQLFilter('display_name', 'filter1', 'login Equals', $options));
        $this->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter2', 'login Begins with (regex)', '`^(?!%).+$`'));
        $this->add_filter(new HTMLTableBeginsWithTextSQLFilter('display_name', 'filter3', 'login Begins with (no regex)'));
        $this->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter4', 'login Ends with (regex)', '`^(?!%).+$`'));
        $this->add_filter(new HTMLTableEndsWithTextSQLFilter('display_name', 'filter5', 'login Ends with (no regex)'));
        $this->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter6', 'login Like (regex)', '`^toto`'));
        $this->add_filter(new HTMLTableLikeTextSQLFilter('display_name', 'filter7', 'login Like (no regex)'));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter8', 'id >'));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter9', 'id > (lower=3)', 3));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter10', 'id > (upper=3)', HTMLTableNumberComparatorSQLFilter::NOT_BOUNDED, 3));
        $this->add_filter(new HTMLTableGreaterThanSQLFilter('user_id', 'filter11', 'id > (lower=1, upper=3)', 1, 3));
        $this->add_filter(new HTMLTableLessThanSQLFilter('user_id', 'filter12', 'id <'));
        $this->add_filter(new HTMLTableGreaterThanOrEqualsToSQLFilter('user_id', 'filter13', 'id >='));
        $this->add_filter(new HTMLTableLessThanOrEqualsToSQLFilter('user_id', 'filter14', 'id <='));
        $this->add_filter(new HTMLTableEqualsToSQLFilter('user_id', 'filter15', 'id ='));
		
	}
}
?>