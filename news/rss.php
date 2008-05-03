<?php
/***************************************************************************
 *                               rss.php
 *                            -------------------
 *   begin                : September 09, 2005
 *   copyright          : (C) 2005 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *  
 *
 ***************************************************************************
 Gestion des flux rss.
***************************************************************************/

//Affichage du contenu au format rss 2.0.
if( defined('PHPBOOST') !== true)
{
    //On genère l'entête xml.
    header("Content-Type: text/xml");

    require_once('../kernel/begin.php');
    require_once('../news/news_begin.php');
    require_once('../kernel/header_no_display.php');
    include_once('../kernel/framework/syndication/feed.class.php');

    $Cache->Load_file('news'); //Requête des configuration générales (new), $CONFIG_NEWS variable globale.

    $feedInformations = array(
        'title' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'date' => gmdate_format('date_format_tiny'),
        'host' => HOST,
        'desc' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
        'lang' => $LANG['xml_lang'],
        'items' => array()
    );

    $result = $Sql->Query_while("SELECT id, title, contents, timestamp
    FROM ".PREFIX."news
    WHERE visible = 1
    ORDER BY timestamp DESC
    " . $Sql->Sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
    while ($row = $Sql->Sql_fetch_assoc($result))
    {
        $rewrited_title = ($CONFIG['rewrite'] == 1) ? '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
        $link = HOST . DIR . '/news/news' . $rewrited_title;
        
        //On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
        $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
        array_push($feedInformations['items'], array(
            'title' => htmlspecialchars(html_entity_decode($row['title'])),
            'link' => $link,
            'desc' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
            'date' => gmdate_format('r', $row['timestamp'])
        ));
    }
    $Sql->Close($result);
    $Sql->Sql_close();

    $Feed = new Feed('news', USE_RSS);
    $Feed->TParse($feedInformations);
}

?>