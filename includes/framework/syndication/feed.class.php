<?php
/*##################################################
 *                         syndication.class.php
 *                            -------------------
 *   begin                : April 21, 2008
 *   copyright         : (C) 2005 Loïc Rouchon
 *   email                : horn@phpboost.com
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

define('ALL_FEEDS', 'all_feeds');
define('RSS', 'rss');
define('ATOM', 'atom');

class Feed
{
    ## Public Methods #
    function Feed($type = ALL_FEEDS)
    /**
    * Constructor
    */
    {
        switch ( $type )
        {
            case ALL_FEEDS:
                $this->feeds[RSS] = new Rss();
                $this->feeds[ATOM] = new Atom();
                break;
            case RSS:
                $this->feeds[RSS] = new Rss();
                break;
            case ATOM:
                $this->feeds[ATOM] = new Atom();
                break;
            case default:
                $this->feeds[RSS] = new Rss();
                $this->feeds[ATOM] = new Atom();
                break;
        }
    }

    function Parse($feedPath, $feedName)
    /**
    * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
    * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
    * the result as a string.
    */
    {
        parsed = '';
        if ( in_array(RSS, array_keys($this->feeds)) &&
             @fopen(trim($feedPath, '/') . '/' . $feedName . RSS, 'r' ) )
        {
            parsed = '';
        }
        elseif ( in_array(ATOM, array_keys($this->feeds)) &&
             @fopen(trim($feedPath, '/') . '/' . $feedName . ATOM, 'r' ) )
        {
            parsed = '';
        }
        return $parsed;
    }

    function Generate($feedFile, $mode = )
    /**
    * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
    */
    {
        parsed = '';
        return $parsed;
    }

    //Génère les fichier du cache, suivant le type demandé.
    function Generate_file($type, $name)
    {
        if( $type == 'javascript' ) //Génération du 1er fichier javascript.
        {
            $file_path = $this->path_cache . $name . '.html';
            $file = fopen($file_path, 'w+'); //Si le fichier n'existe pas on le crée avec droit d'écriture et lecture.
            fputs($file, "document.write('" . str_replace('\'', '\\\'', $this->flux) . "');");
            fclose($file);
        }
        elseif( $type == 'php' ) //Génération du 2ème fichier PHP.
        {
            $file_path2 = $this->path_cache . $name . '.html';
            $file = fopen($file_path2, 'w+'); //Si le fichier n'existe pas on le crée avec droit d'écriture et lecture.
            fputs($file, $this->flux);
            fclose($file);
        }
    }


    ## Private Methods ##
    //Charge le rss non parsé.
    function load_rss($path_flux)
    {
        if( $this->mode == 'include')
        {
            if( @include('..' . $path_flux) )
                $this->get_rss($RSS_flux); //Récupère le contenu du rss directement.
            else
                $this->flux = '';
        }
        else
        {
            $file = file_get_contents_emulate(HOST . DIR . $path_flux);
            if( $file !== false )
            {
                if( preg_match('`<item>(.*)</item>`is', $file) )
                    $this->parse_rss($file); //Parse le rss chargé
            }
            else
                $this->flux = '';
        }
    }

    //Parse le rss chargé
    function parse_rss($line)
    {
        $array_items = explode('<item>', $line);
        $lenght_array = count($array_items);

        $this->flux = '<ul>';
        for($i = 1; $i < $lenght_array; $i++)
        {
            $url = preg_match('`<link>(.*)</link>`is', $array_items[$i], $url) ? $url[1] : '';
            $title = preg_match('`<title>(.*)</title>`is', $array_items[$i], $title) ? $title[1] : '';

            //Conversion heure GMT -> J/M/A.
            $date = preg_match('`<pubDate>(.*)</pubDate>`is', $array_items[$i], $date) ? gmdate_format('date_format_tiny', strtotime($date[1])) : '';

            $this->flux .= '<li>' . $date . ' <a href="' . $url . '">' . $title . '</a></li>';
        }
        $this->flux .= '</ul>';
    }

    //Récupère le contenu du rss directement.
    function get_rss($rss_flux)
    {
        $this->flux = '<ul>';
        foreach($rss_flux as $key => $value)
            $this->flux .= '<li>' . $value[2] . ' <a href="' . $value[1] . '">' . $value[0] . '</a></li>';
        $this->flux .= '</ul>';
    }

    ## Private attributes ##
    var $path_cache = ''; //Chemin du cache, lien relatif sur le serveur.
    var $feeds = array();
}

?>