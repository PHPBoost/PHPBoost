<?php
/***************************************************************************
 *                              syndication.php
 *                            -------------------
 *   begin                : September 09, 2005
 *   copyright            : (C) 2008 Horn
 *   email                : horn@phpboost.com
 *
 *  
 *
 ***************************************************************************
 Print the feed in the RSS 2.0 or ATOM 1.0 format
***************************************************************************/

//Header's generation
header("Content-Type: application/xml; charset=iso-8859-1");

require_once('../kernel/begin.php');

$mode = retrieve(GET, 'feed', 'rss');
if ( !(($mode == 'atom') || ($mode == 'rss')) )
    $mode = 'rss';

if ( $file = @file_get_contents_emulate('../cache/syndication/news.' . $mode) )
{   // If the file exist, we print it
    echo $file;
}
else
{   // Otherwise, we regenerate it before printing it
	require_once('../news/news_begin.php');
	require_once('../kernel/header_no_display.php');
    require_once('../kernel/framework/syndication/feed.class.php');
    
    // Generation of the feed's headers
    $feedInformations = array(
        'title' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'date' => gmdate_format('Y-m-d') . 'T' . gmdate_format('h:m:s') . 'Z',
        'link' => trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'news/syndication.php?feed=' . $mode,
        'host' => HOST,
        'desc' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'lang' => $LANG['xml_lang'],
        'items' => array()
    );

    // Load the new's config
    $Cache->Load_file('news');
    // Last news
    $result = $Sql->Query_while("SELECT id, title, contents, timestamp
        FROM ".PREFIX."news
        WHERE visible = 1
        ORDER BY timestamp DESC
    " . $Sql->Sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);

    // Generation of the feed's items
    while ($row = $Sql->Sql_fetch_assoc($result))
    {
        // Rewriting
        if ( $CONFIG['rewrite'] == 1 )
            $rewrited_title = '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
        else
            $rewrited_title = '.php?id=' . $row['id'];
        $link = HOST . DIR . '/news/news' . $rewrited_title;
        
        // XML text's protection
        $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
        array_push($feedInformations['items'], array(
            'title' => htmlspecialchars(html_entity_decode($row['title'])),
            'link' => $link,
            'guid' => $link,
            'desc' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
            'date' => gmdate_format('Y-m-d',$row['timestamp']) . 'T' . gmdate_format('h:m:s',$row['timestamp']) . 'Z',
        ));
    }
    $Sql->Close($result);
    $Sql->Sql_close();
    
    $Feed = new Feed('news', $mode == 'atom' ? USE_ATOM : USE_RSS);
    $Feed->Generate($feedInformations); // Create the feed's cache
    $Feed->TParse();                    // Print the feed
    
	require_once('../kernel/footer_no_display.php');
}

?>