<?php
/*##################################################
 *                             pagination.class.php
 *                            -------------------
 *   begin                : June 30, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

define('NO_PREVIOUS_NEXT_LINKS', false); //Lien précédent/suivant.
define('LINK_START_PAGE', false); //Lien sur la première page.

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class enables you to manage easily a pagination system.
 * It's very useful when you have a lot of items and you cannot display all of them.
 * It also can generate the where clause to insert in your SQL query which selects the items.
 * @package {@package}
 */
class DeprecatedPagination
{
	private $page; //Valeur courante de la page.
	private $nbr_start_links = 3; //Nombre de liens affichés en début de chaîne.
	private $nbr_end_links = 3; //Nombre de liens affichés en fin de chaîne.
	private $var_page;
	
	/**
	* @desc Buils a Pagination.
	*/
	public function __construct()
	{
	}

	/**
	 * @desc Returns a list of links between pages.
	 * @param string $path Adress with the url() function which abstracts the management of url rewritting in pagination links.
	 * You have to specify where the value of page will be placed in the adresse with %d (it will be replaced automatically)
	 * Example: url('page.php?p=%d', 'page-%d.php') //p is the variable name passed by the arguement $var_page.
	 * @param string $total_msg The total number of items.
	 * @param string $var_page The variable name used to get the page in the adress (in most case "p" is used).
	 * @param string $nbr_msg_page The number of items per page.
	 * @param string $nbr_max_link The maximum number of links displayed.
	 * @param string $font_size Links font size.
	 * @param string $previous_next Display links before and after pagination links, to go to the next/previous page.
	 * @param string $link_start_page Underline link to the current page.
	 * @return string Pagination links.
	 */
	public function display($path, $total_msg, $var_page, $nbr_msg_page, $nbr_max_link, $font_size = 11, $previous_next = true, $link_start_page = true)
	{
		if ($total_msg > $nbr_msg_page)
		{
			//Initialisations.
			$links = ''; //Chaîne à retourner.

			$this->page = $this->get_var_page($var_page);
			$nbr_page = ceil($total_msg / $nbr_msg_page); //Calcul du nombre page.
			if ($nbr_page == 1)
			{
				return '';
			}

			$this->page = $this->check_page($nbr_page); //Page valide.

			//Affichage lien suivant « (si activé !)
			if ($this->page != 1 && $nbr_page > 1 && $previous_next === true) //Plus qu'une page, et page différente de celle par défaut => affichage du lien.
			{
				$links .= '&nbsp;<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $this->page - 1) . '">&laquo;</a>&nbsp;';
			}

			$page_max_end = $nbr_page - $this->nbr_end_links; //Numéro de la page $this->nbr_end_links avant la dernière page.
			$page_current_max = $this->page + $nbr_max_link; //Numéro de la page $nbr_max_link après la page courante.
			$page_current_min = $this->page - $nbr_max_link; //Numéro de la page $nbr_max_link avant la page courante.

			for ($i = 1; $i <= $nbr_page; $i++)
			{
				if ($i == $this->page && $link_start_page) //Page courante.
				{
					$links .= '&nbsp;<span class="text_strong" style="font-size:' . $font_size . 'px;text-decoration: underline;">' . $this->page . '</span>&nbsp;';
				}
				elseif ($i <= $this->nbr_start_links || $i > $page_max_end || ($i <= $page_current_max && $i >= $page_current_min))
				{
					$links .= '&nbsp;<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $i) . '">' . $i . '</a>&nbsp;';
				}
				else //Affiche les $nbr_max_link liens précédents/suivant la page courante, si dépasse le $this->nbr_start_links ou le $this->nbr_end_links
				{
					if ($i >= $this->nbr_start_links && $i <= $page_current_min)
					{
						$i = $page_current_min - 1; //Saut conditionnel d'itération, envoi à la page: $this->page - $nbr_max_link.
						$links .= '...';
					}
					elseif ($i >= $page_current_max && $i <= $page_max_end)
					{
						$i = $page_max_end; //Saut conditionnel d'itération, envoi à la page: $this->page + $nbr_max_link.
						$links .= '...';
					}
				}
			}

			//Affichage lien précédent » (si activé !)
			if ($this->page != $nbr_page && $nbr_page > 1 && $previous_next === true) //Plusieurs page et page courante différente de la dernière => affichage du lien.
			{
				$links .= '&nbsp;<a style="font-size:' . $font_size . 'px;" href="' . sprintf($path, $this->page + 1) . '">&raquo;</a>';
			}

			return $links; //On retourne la chaîne formatée.
		}
		else
		return '';
	}

	/**
	 * @desc Returns the first message of the current page displayed.
	 * It usually used in SQL queries. Example :
	 * $Sql->query_while("SELECT n.contents FROM " . PREFIX . "news n
	 *	" . $Sql->limit($Pagination->get_first_msg($CONFIG_NEWS['pagination_news'], 'p'), $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
	 * For further informations, refer to the db package documentation.
	 * @param int $nbr_msg_page Number of messages per page.
	 * @param string $var_page The variable name used to get the page in the adress (in most case "p" is used).
	 * @return int the first message of the current page displayed.
	 */
	public function get_first_msg($nbr_msg_page, $var_page)
	{
		$page = !empty($_GET[$var_page]) ? NumberHelper::numeric($_GET[$var_page]) : 1;
		$page = $page > 0 ? $page : 1;
		return (($page - 1) * $nbr_msg_page);
	}

	/**
	 * @return int Return the current page
	 */
	public function get_current_page()
	{
		return $this->get_var_page($this->var_page);
	}

	/**
	* @param string $var_page The var name used to get the page in the adress (in most case "p" is used).
	* @return int Return the value of the var page
	*/
	public function get_var_page($var_page)
	{
		$_GET[$var_page] = isset($_GET[$var_page]) ? NumberHelper::numeric($_GET[$var_page]) : 0;
		if (!empty($_GET[$var_page]))
		{
			return $_GET[$var_page];
		}
		else
		{
			return 1;
		}
	}

	/**
	 * @desc Checks the validity of the page required, otherwise redirect to an error page.
	 * @param int $nbr_page Number total of page.
	 * @return int Return current page if exist, otherwise redirect to an error page.
	 */
	private function check_page($nbr_page)
	{
		if ($this->page < 0) //Erreur => redirection!
		{
			$error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
		}
		elseif ($this->page > $nbr_page) //Erreur => redirection!
		{
			$error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
		}

		return $this->page;
	}
}

?>