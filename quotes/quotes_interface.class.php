<?php
/*##################################################
 *                              quotes_interface.class.php
 *                            -------------------
 *   begin                : October 14, 2008
 *   copyright         : (C) 2008 Alain GANDON based on Guestbook_interface.class.php
 *   email                : 
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

// Classe ForumInterface qui hérite de la classe ModuleInterface
class QuotesInterface extends ModuleInterface
{
    ## Public Methods ##
    /**
	* @method  Constructeur de l'objet
	*/
    function QuotesInterface() //Constructeur de la classe
    {
        parent::ModuleInterface('quotes');
    }
    
    /**
	*  @method  Mise à jour du cache
	*/
	function get_cache()
	{
		global $Sql;
	
		$quotes_code = 'global $CONFIG_QUOTES;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_QUOTES = sunserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'quotes'", __LINE__, __FILE__));
		$CONFIG_QUOTES = is_array($CONFIG_QUOTES) ? $CONFIG_QUOTES : array();
		
		if (isset($CONFIG_QUOTES['quotes_forbidden_tags']))
			$CONFIG_QUOTES['quotes_forbidden_tags'] = unserialize($CONFIG_QUOTES['quotes_forbidden_tags']);
			
		$location = $Sql->query("SELECT location FROM ".PREFIX."menus WHERE name ='quotes'", __LINE__, __FILE__);
		$vertical = array('left', 'right');
		if (in_array($location, $vertical)) {
			$CONFIG_QUOTES['tpl_vertical'] = TRUE;
		} else {
			$CONFIG_QUOTES['tpl_vertical'] = FALSE;
		}
			
		$quotes_code .= '$CONFIG_QUOTES = ' . var_export($CONFIG_QUOTES, true) . ';' . "\n";
		
		$quotes_code .= "\n\n" . 'global $_quotes_rand_msg;' . "\n";
		$quotes_code .= "\n" . '$_quotes_rand_msg = array();' . "\n";
		$result = $Sql->query_while("SELECT q.id, q.user_id, q.timestamp, q.contents, q.author,	m.login AS mlogin
		FROM ".PREFIX."quotes q
		LEFT JOIN ".PREFIX."member m ON m.user_id = q.user_id
		ORDER BY q.timestamp DESC" . $Sql->limit(0, 10), __LINE__, __FILE__);	
		while ($row = $Sql->fetch_assoc($result))
		{
			$quotes_code .= '$_quotes_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'contents\' => ' . var_export(substr_html(strip_tags($row['contents']), 0), true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ', \'login\' => ' . var_export($row['mlogin'], true) . ');' . "\n";
		}
		$Sql->query_close($result);
		
		return $quotes_code;
	}

    /**
	*  @method  Actions sur changement de jour
	*/
	function on_changeday()
	{
		global $Cache;
		
		$Cache->generate_module_file('quotes');
	}

    /**
	*  @method  Renvoie le formulaire de recherche du module
	*/
    function get_search_form($args=null)
    {
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        load_module_lang('quotes');
        global $CONFIG, $LANG;
        
        $Tpl = new Template('quotes/quotes_search_form.tpl');
        
        if ( empty($args['QuotesWhere']) || !in_array($args['QuotesWhere'], explode(',','author,contents,all')) )
            $args['QuotesWhere'] = 'all';

        $Tpl->assign_vars(Array(
            'L_WHERE' => $LANG['quotes_search_where'],
            'IS_AUTHOR_SELECTED' => $args['QuotesWhere'] == 'author'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['QuotesWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['QuotesWhere'] == 'all'? ' selected="selected"': '',
            'L_AUTHOR' => $LANG['quotes_author'],
            'L_CONTENTS' => $LANG['quotes_contents']
        ));
        
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }

    /**
	*  @method  Renvoie la liste des arguments de la méthode <GetSearchRequest>
	*/
    function get_search_args()
    {
        return Array('QuotesWhere');
    }

    /**
	*  @method  Renvoie la requête de recherche dans le module
	*/
    function get_search_request($args)
    {
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
        if ( empty($args['QuotesWhere']) || !in_array($args['QuotesWhere'], explode(',','author,contents,all')) )
            $args['QuotesWhere'] = 'all';
        
		switch ($args['QuotesWhere']) {
			case 'author':
				$req = "SELECT "
				.$args['id_search']." AS `id_search`,
				q.author AS `title`,
				q.id AS `id_content`,
				MATCH(q.author) AGAINST('".$args['search']."') * " . $weight . " AS `relevance`
				FROM ".PREFIX."quotes AS q
				WHERE MATCH(q.author) AGAINST('".$args['search']."')";
				break;
			case 'contents':
	            $req = "SELECT ";
				break;
			case 'all':
			default:
	            $req = "SELECT ";
		}
        return $req;
    }
	
}

?>