<?php
/*##################################################
 *                              newsletterExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
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



class NewsletterExtensionPointProvider extends ExtensionPointProvider
{
    ## Public Methods ##
    function __construct() //Constructeur de la classe ForumInterface
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('newsletter');
    }
    
    //Récupération du cache.
	function get_cache()
	{
		global $CONFIG;
		
		//Configuration de la newsletter
		$code = 'global $_NEWSLETTER_CONFIG;' . "\n" . '$_NEWSLETTER_CONFIG = array();' . "\n";
		$NEWSLETTER_CONFIG = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'newsletter'", __LINE__, __FILE__));
		if (is_array($NEWSLETTER_CONFIG))
		{
			$mails = MailServiceConfig::load()->get_administrators_mails();
			$code .= '$_NEWSLETTER_CONFIG[\'sender_mail\'] = ' . var_export(!empty($NEWSLETTER_CONFIG['sender_mail']) ? $NEWSLETTER_CONFIG['sender_mail'] : $mails[0], true) . ';' . "\n";
			$code .= '$_NEWSLETTER_CONFIG[\'newsletter_name\'] = ' . var_export(!empty($NEWSLETTER_CONFIG['newsletter_name']) ? $NEWSLETTER_CONFIG['newsletter_name'] : $CONFIG['site_name'], true) . ';' . "\n";
		}
		
		return $code;
	}
}

?>