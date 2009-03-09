<?php
/*##################################################
 *                             editor.class.php
 *                            -------------------
 *   begin                : July 5 2008
 *   copyright          : (C) 2008 Régis Viarre
 *   email                :  crowkait@phpboost.com
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

class ContentEditor
{
    function ContentEditor($language_type = null)
    {
        global $CONFIG;
        if ($language_type !== null)
        {
            $this->set_language($language_type);
        }

        $this->forbidden_tags =& $CONFIG['forbidden_tags'];
    }

    //Balises interdites.
    function set_forbidden_tags(&$forbidden_tags)
    {
        $this->forbidden_tags = $forbidden_tags;
    }

    // Getter des balises interdites
    function get_forbidden_tags()
    {
        return $this->forbidden_tags;
    }

    //Identifiant du textarea de destination.
    function set_identifier($identifier)
    {
        $this->identifier = $identifier;
    }

    //Template alternatif.
    function set_template(&$template)
    {
        $this->template = $template;
    }

    //Fonction qui renvoie le template courant
    function get_template()
    {
        if (!is_object($this->template) || strtolower(get_class($this->template)) != 'template')
        {
            return new template('framework/content/editor.tpl');
        }
        else
        {
            return $this->template;
        }
    }

    ## Private ##
    var $language_type = DEFAULT_LANGUAGE; //Langage type
    var $forbidden_tags = array();
    var $identifier = 'contents';
    var $template = null;
}

?>