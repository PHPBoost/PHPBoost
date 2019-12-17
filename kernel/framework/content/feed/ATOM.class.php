<?php
/**
 * This class could load a feed by its url or by a FeedData element and
 * export it to the ATOM format
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 2.0 - 2008 04 21
 * @contributor mipel <mipel@phpboost.com>
*/

class ATOM extends Feed
{
	const DEFAULT_ATOM_TEMPLATE = 'framework/content/syndication/atom.tpl';

    ## Public Methods ##
    /**
     * Builds a new ATOM object
     * @param string $module_id its module_id
     * @param string $feed_name the feeds name / type. default is Feed::DEFAULT_FEED_NAME
     * @param int $id_cat the feed category id
     */
    public function __construct($module_id, $feed_name = Feed::DEFAULT_FEED_NAME, $id_cat = 0)
    {
        parent::__construct($module_id, $feed_name, $id_cat);
        $this->tpl = new FileTemplate(self::DEFAULT_ATOM_TEMPLATE);
    }

    /**
     * Loads a feed by its url
     * @param string $url the feed url
     */
    public function load_file($url)
    {
        if (($file = @file_get_contents($url)) !== false)
        {
            $this->data = new FeedData();
            if (preg_match('`<entry>(.*)</entry>`isu', $file))
            {
                $expParsed = explode('<entry>', $file);
                $nbItems = (count($expParsed) - 1) > $nbItems ? $nbItems : count($expParsed) - 1;

                $this->data->set_date(preg_match('`<updated>(.*)</updated>`isu', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_title(preg_match('`<title>(.*)</title>`isu', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_link(preg_match('`<link href="(.*)"/>`isu', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_host(preg_match('`<link href="(.*)"/>`isu', $expParsed[0], $var) ? $var[1] : '');

                for ($i = 1; $i <= $nbItems; $i++)
                {
					$item = new FeedItem();
                    $item->set_title(preg_match('`<title>(.*)</title>`isu', $expParsed[$i], $title) ? $title[1] : '');
                    $item->set_link(preg_match('`<link href="(.*)"/>`isu', $expParsed[$i], $url) ? $url[1] : '');
                    $item->set_guid(preg_match('`<id>(.*)</id>`isu', $expParsed[$i], $guid) ? $guid[1] : '');
                    $item->set_desc(preg_match('`<summary>(.*)</summary>`isu', $expParsed[$i], $desc) ? $desc[1] : '');
                    $item->set_date(preg_match('`<updated>(.*)</updated>`isu', $expParsed[$i], $date) ? new Date(strtotime($date[1]), Timezone::SERVER_TIMEZONE) : null);

                 	$enclosure = preg_match('`<enclosure rel="enclosure" url="(.*)" length="(.*)" type="(.*)" />`isu', $expParsed[$i]);
                    if ($enclosure)
                    {
                    	$enclosure_item = new FeedItemEnclosure();
                    	$enclosure_item->set_lenght($enclosure[2]);
                    	$enclosure_item->set_type($enclosure[3]);
                    	$enclosure_item->set_url($enclosure[1]);
                    	$item->set_enclosure($enclosure_item);
                    }

                    $this->data->add_item($item);
                }
                return true;
            }
            return false;
        }
        return false;
    }
}
?>
