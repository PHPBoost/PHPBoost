<?php
/*##################################################
 *                               template.class.php
 *                            -------------------
 *   begin                : Februar 12, 2006
 *   copyright            : (C) 2006 Régis Viarre, Loïc Rouchon
 *   email                : mickaelhemri@gmail.com, horn@phpboost.com
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
 * The PHPboost template engine is actually based on sections of code from phpBB3 templates
###################################################*/

define('TEMPLATE_STRING_MODE', true);

class Template
{
	## Public Attribute ##
	var $_var = array(); //Tableau contenant les variables de remplacement des variables simples.
	var $_block = array(); //Tableau contenant les variables de remplacement des variables simples.

    // Constructeur
    function Template($tpl = '')
    {
		global $CONFIG, $Member;
		
        $this->tpl = $this->check_file($tpl);
        $this->files[$this->tpl] = $this->tpl;
		if( !empty($tpl) )
		{
			$member_connected = $Member->Check_level(MEMBER_LEVEL);
			$this->Assign_vars(array(
				'SID' => SID,
				'THEME' => $CONFIG['theme'],
				'LANG' => $CONFIG['lang'],
				'C_MEMBER_CONNECTED' => $member_connected,
				'C_MEMBER_NOTCONNECTED' => !$member_connected,
				'PATH_TO_ROOT' => PATH_TO_ROOT
				));
		}
    }
	
	//Stock les différents tpl en cours de traitement.
	function Set_filenames($array_tpl)
	{
		foreach($array_tpl as $parse_name => $filename)
			$this->files[$parse_name] = $this->check_file($filename);
	}
	
	//Récupération du chemin des données du module.
	function Module_data_path($module)
	{
		if( isset($this->module_data_path[$module]) )
			return $this->module_data_path[$module];
		return '';
	}
	
	//Stock les variables simple.
	function Assign_vars($array_vars)
	{
		foreach($array_vars as $key => $val)
			$this->_var[$key] = $val;
	}
		
	//Stock les variables des différents blocs.
	function Assign_block_vars($block_name, $array_vars)
	{
		if( strpos($block_name, '.') !== false ) //Bloc imbriqué.
		{
			$blocks = explode('.', $block_name);
			$blockcount = count($blocks) - 1;

			$str = &$this->_block;
			for($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			$str[$blocks[$blockcount]][] = $array_vars;
		}
		else
			$this->_block[$block_name][] = $array_vars;
	}
	
	//Supprime un bloc.
	function Unassign_block_vars($block_name)
	{
		if( isset($this->_block[$block_name]) )
			unset($this->_block[$block_name]);
	}

    //
    function parse($stringMode = false)
    {
        if ( $stringMode )
            return $this->Pparse($this->tpl, $stringMode);
        else $this->Pparse($this->tpl, $stringMode);
    }
    
	//Affichage du traitement du tpl.
	function Pparse($parse_name, $stringMode = false)
	{
        $this->stringMode = $stringMode;
		$file_cache_path = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(array('/', '.', '..', 'tpl', 'templates'), array('_', '', '', '', 'tpl'), $this->files[$parse_name]), '_');
        if( $stringMode )
            $file_cache_path .= '_str';
        $file_cache_path .= '.php';

		//Vérification du statut du fichier de cache associé au template.
		if( !$this->check_cache_file($this->files[$parse_name], $file_cache_path) )
		{
			//Chargement du template.
			if( !$this->load_tpl($parse_name) )
				return '';

			//Parse
			$this->_parse($parse_name, $stringMode);
			$this->clean(); //On nettoie avant d'envoyer le flux.
			$this->save($file_cache_path, $stringMode); //Enregistrement du fichier de cache.
		}
        
		include($file_cache_path);
		
        if( $this->stringMode )
            return $tplString;
	}

	// Object  cloner
	function Copy()
	{
		$copy = new Template();
		
		$copy->tpl = $this->tpl;
		$copy->template = $this->template;
		$copy->files = $this->files;
		$copy->module_data_path = $this->module_data_path;
	    $copy->stringMode = $this->stringMode;
		
		return $copy;
	}
	
	
	## Private Methods ##
	//Vérification de l'existence du tpl perso, sinon tentative de chargement du tpl de base.
	function check_file($filename)
	{
		global $CONFIG;
		
		$filename = trim($filename, '/');
		$i = strpos($filename, '/');
		$module = substr($filename, 0, $i);
		$file = substr($filename, $i);

		if( empty($file) ) //Template du thème (noyau)
			return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $filename;
		else //Module
		{
			//module data path
			if( !isset($this->module_data_path[$module]) )
			{
				if( is_dir(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $module . '/images') )
					$this->module_data_path[$module] = PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $module;
				else	
					$this->module_data_path[$module] = PATH_TO_ROOT . '/' . $module . '/templates'; 
			}
			
			if( file_exists(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $module . '/' . $file) )
				return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $module . '/' . $file;
			else
				return PATH_TO_ROOT . '/' . $module . '/templates/' . $file;
		}
	}
	
	//Vérifie le statut du fichier en cache, il sera regénéré s'il n'existe pas ou si il est périmé.
	function check_cache_file($tpl_path, $file_cache_path)
	{
		//fichier expiré
		if( file_exists($file_cache_path) )
		{	
			if( @filemtime($tpl_path) > @filemtime($file_cache_path) || @filesize($file_cache_path) === 0 ) 
				return false;
			else
				return true;
		}
		return false;
	}
 
	//Charge un tpl.
	function load_tpl($parse_name)
	{
		if( !isset($this->files[$parse_name]) )
			return false;
			
		$this->template = @file_get_contents_emulate($this->files[$parse_name]); //Charge le contenu du fichier tpl.
		if( $this->template === false )
			die('Template->load_tpl(): Le chargement du fichier ' . $this->files[$parse_name] . ' pour parser ' . $parse_name . ' a échoué');
		if( empty($this->template) )
			die('Template->load_tpl(): Le fichier ' . $this->files[$parse_name] . ' pour parser ' . $parse_name . ' est vide');
			
		return true;
	}
	
	//Inclusion d'un template dans un autre.
	function tpl_include($parse_name)
	{
 		if( isset($this->files[$parse_name]) )
        {
            if( $this->stringMode )
                return $this->Pparse($parse_name, $this->stringMode);
            else
                $this->Pparse($parse_name, $this->stringMode);
        }
	}
    
    //Parse du tpl.
    function _parse($parse_name)
    {
        if( $this->stringMode )
        {
            $this->template = '<?php $tplString = \'' . str_replace(array('\\', '\''), array('\\\\', '\\\''), $this->template) . '\' ?>';
            //Remplacement des variables simples.
            $this->template = preg_replace('`{([\w]+)}`i', '\' . (isset($this->_var[\'$1\']) ? $this->_var[\'$1\'] : \'\') . \'', $this->template);
            $this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'parse_blocks_vars'), $this->template);
            
            //Parse des blocs imbriqués ou non.
            $this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'parse_blocks'), $this->template);
            $this->template = preg_replace('`# END [\w\.]+ #`', '\';'."\n".'}'."\n".'$tplString .= \'', $this->template);
            
            //Remplacement des blocs conditionnels.
            $this->template = preg_replace_callback('`# IF ([\w\.]+) #`', array($this, 'parse_conditionnal_blocks'), $this->template);
            $this->template = preg_replace('`# ELSE #`', '\';'."\n".'} else {'."\n".'$tplString .= \'', $this->template);
            $this->template = preg_replace('`# ENDIF #`', '\';'."\n".'}'."\n".'$tplString .= \'', $this->template);
            
            //Remplacement des includes.
            $this->template = preg_replace('`# INCLUDE ([\w]+) #`', '\';'."\n".'$tplString .= $this->tpl_include(\'$1\');'."\n".'$tplString .= \'', $this->template);
        }
        else
        {
            // Protection des injections PHP et affichages des tags XML
            $this->template = preg_replace_callback('`\<\?(.*)\?\>`i', array($this, 'protectFromInject'), $this->template);
            
            //Remplacement des variables simples.
            $this->template = preg_replace('`{([\w]+)}`i', '<?php echo (isset($this->_var[\'$1\']) ? $this->_var[\'$1\'] : \'\'); ?>', $this->template);
            $this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'parse_blocks_vars'), $this->template);
            
            //Parse des blocs imbriqués ou non.
            $this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'parse_blocks'), $this->template);
            $this->template = preg_replace('`# END [\w\.]+ #`', '<?php } ?>', $this->template);
            
            //Remplacement des blocs conditionnels.
            $this->template = preg_replace_callback('`# IF ([\w\.]+) #`', array($this, 'parse_conditionnal_blocks'), $this->template);
            $this->template = preg_replace('`# ELSE #`', '<?php } else { ?>', $this->template);
            $this->template = preg_replace('`# ENDIF #`', '<?php } ?>', $this->template);
            
            //Remplacement des includes.
            $this->template = preg_replace('`# INCLUDE ([\w]+) #`', '<?php $this->tpl_include(\'$1\'); ?>', $this->template);
        }
    }
    
    function protectFromInject($mask)
    {
        return '<?php echo \'<?' . str_replace(array('\\', '\''), array('\\\\', '\\\''), trim($mask[1])) . '?>\'; ?>';
    }

    
	//Remplacement des variables de type bloc.
	function parse_blocks_vars($blocks)
	{
		if( isset($blocks[1]) )
		{	
			$array_block = explode('.', $blocks[1]);
			$varname = array_pop($array_block);
			$last_block = array_pop($array_block);

            if( $this->stringMode )
                return '\'.(isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) ? $_tmpb_' . $last_block . '[\'' . $varname . '\'] : \'\').\'';
            else
			    return '<?php echo (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) ? $_tmpb_' . $last_block . '[\'' . $varname . '\'] : \'\'); ?>';
		}		
		return '';
	}
    
	//Remplacement des blocs.
	function parse_blocks($blocks)
	{
		if( isset($blocks[1]) )
		{	
			if( strpos($blocks[1], '.') !== false ) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[1]);
				$current_block = array_pop($array_block);
				$previous_block = array_pop($array_block);
                
                if( $this->stringMode )
                    return '\';'."\n".'if( !isset($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) || !is_array($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) ) $_tmpb_' . $previous_block . '[\'' . $current_block . '\'] = array();' . "\n" . 'foreach($_tmpb_' . $previous_block . '[\'' . $current_block . '\'] as $' . $current_block . '_key => $' . $current_block . '_value) {' . "\n" . '$_tmpb_' . $current_block . ' = &$_tmpb_' . $previous_block . '[\'' . $current_block . '\'][$' . $current_block . '_key];'."\n".'$tplString .= \'';
                else
                    return '<?php if( !isset($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) || !is_array($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) ) $_tmpb_' . $previous_block . '[\'' . $current_block . '\'] = array();' . "\n" . 'foreach($_tmpb_' . $previous_block . '[\'' . $current_block . '\'] as $' . $current_block . '_key => $' . $current_block . '_value) {' . "\n" . '$_tmpb_' . $current_block . ' = &$_tmpb_' . $previous_block . '[\'' . $current_block . '\'][$' . $current_block . '_key]; ?>';
			}
			else
            {
                if( $this->stringMode )
                    return '\';'."\n".'if( !isset($this->_block[\'' . $blocks[1] . '\']) || !is_array($this->_block[\'' . $blocks[1] . '\']) ) $this->_block[\'' . $blocks[1] . '\'] = array();' . "\n" . 'foreach($this->_block[\'' . $blocks[1] . '\'] as $' . $blocks[1] . '_key => $' . $blocks[1] . '_value) {' . "\n" . '$_tmpb_' . $blocks[1] . ' = &$this->_block[\'' . $blocks[1] . '\'][$' . $blocks[1] . '_key];'."\n".'$tplString .= \'';
                else
				    return '<?php if( !isset($this->_block[\'' . $blocks[1] . '\']) || !is_array($this->_block[\'' . $blocks[1] . '\']) ) $this->_block[\'' . $blocks[1] . '\'] = array();' . "\n" . 'foreach($this->_block[\'' . $blocks[1] . '\'] as $' . $blocks[1] . '_key => $' . $blocks[1] . '_value) {' . "\n" . '$_tmpb_' . $blocks[1] . ' = &$this->_block[\'' . $blocks[1] . '\'][$' . $blocks[1] . '_key]; ?>';
            }
		}
		return '';
	}
	
	//Remplacement des blocs conditionnels.
	function parse_conditionnal_blocks($blocks)
	{
		if( isset($blocks[1]) ) 
		{
			if( strpos($blocks[1], '.') !== false ) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[1]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);

                if( $this->stringMode )
                    return '\';'."\n".'if( isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && $_tmpb_' . $last_block . '[\'' . $varname . '\'] ) {'."\n".'$tplString .= \'';
                else
				    return '<?php if( isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && $_tmpb_' . $last_block . '[\'' . $varname . '\'] ) { ?>';
			}
			else
            {
                if( $this->stringMode )
                    return '\';'."\n".'if( isset($this->_var[\'' . $blocks[1] . '\']) && $this->_var[\'' . $blocks[1] . '\'] ) {'."\n".'$tplString .= \'';
                else
                    return '<?php if( isset($this->_var[\'' . $blocks[1] . '\']) && $this->_var[\'' . $blocks[1] . '\'] ) { ?>';
		    }
        }
		return '';
	}
	
	//Nettoyage des commentaires, et blocs et variables non utilisés.
	function clean()
	{
		$this->template = preg_replace(
			array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'), 
			array('', '', '', ''), 
		$this->template);

        //Evite à l'interpréteur PHP du travail inutile.
        if( $this->stringMode )
        {
            $this->template = str_replace('$tplString .= \'\';', '', $this->template);
            $this->template = preg_replace(array('`[\n]{2,}`', '`[\r]{2,}`', '`[\t]{2,}`', '`[ ]{2,}`'), array('', '', '', ''), $this->template);
        }
        else
        {
            $this->template = preg_replace('` \?><\?php `', '', $this->template);
            $this->template = preg_replace('` \?>[\s]+<\?php `', "echo ' ';", $this->template);
            $this->template = preg_replace("`echo ' ';echo `", "echo ' ' . ", $this->template);
            $this->template = preg_replace("`''\);echo `", "'') . ", $this->template);
        }
	}
	
	//Enregistrement du fichier de cache, avec pose préalable d'un verrou.
	function save($file_cache_path, $stringMode = false)
	{
		if( $file = @fopen($file_cache_path, 'wb') )
		{
			@flock($file, LOCK_EX);
            
            @fwrite($file, $this->template);
			@flock($file, LOCK_UN);
			@fclose;
			
			@chmod($file_cache_path, 0666);
		}
	}
	

	## Private Attribute ##
    var $tpl = ''; // Nom du fichier de template
	var $template = ''; //Chaîne contenant le tpl en cours de parsage.
	var $files = array(); //Tableau contenant le chemin vers le tpl (vérifié).
	var $module_data_path = array(); //Chemin vers les données du module.
    var $stringMode; // Type de parsage à effectuer, inclusion du tpl parsé ou retourne une chaine.
}

?>