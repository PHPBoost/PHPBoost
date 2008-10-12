<?php
/*##################################################
 *                            template.class.php
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
define('AUTO_LOAD_FREQUENT_VARS', true);
define('DO_NOT_AUTO_LOAD_FREQUENT_VARS', FALSE);

class Template
{
	## Public Attribute ##
	var $_var = array(); //Tableau contenant les variables de remplacement des variables simples.
	var $_block = array(); //Tableau contenant les variables de remplacement des variables simples.

    // Constructeur
    function Template($tpl = '', $auto_load_vars = AUTO_LOAD_FREQUENT_VARS)
    {
        if( !empty($tpl) )
		{
			global $CONFIG, $User;

			$this->tpl = $this->_check_file($tpl);
			$this->files[$this->tpl] = $this->tpl;
			if( $auto_load_vars )
			{
				$member_connected = $User->check_level(MEMBER_LEVEL);
				$this->assign_vars(array(
					'SID' => SID,
					'THEME' => $CONFIG['theme'],
					'LANG' => $CONFIG['lang'],
					'C_MEMBER_CONNECTED' => $member_connected,
					'C_MEMBER_NOTCONNECTED' => !$member_connected,
					'PATH_TO_ROOT' => PATH_TO_ROOT
				));
			}
		}
    }
	
	//Stock les différents tpl en cours de traitement.
	function set_filenames($array_tpl)
	{
		foreach($array_tpl as $parse_name => $filename)
			$this->files[$parse_name] = $this->_check_file($filename);
	}
	
	//Récupération du chemin des données du module.
	function get_module_data_path($module)
	{
		if( isset($this->module_data_path[$module]) )
			return $this->module_data_path[$module];
		return '';
	}
	
	//Stock les variables simple.
	function assign_vars($array_vars)
	{
		foreach($array_vars as $key => $val)
			$this->_var[$key] = $val;
	}
		
	//Stock les variables des différents blocs.
	function assign_block_vars($block_name, $array_vars)
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
	function unassign_block_vars($block_name)
	{
		if( isset($this->_block[$block_name]) )
			unset($this->_block[$block_name]);
	}

    //
    function parse($stringMode = false)
    {
        if ( $stringMode )
            return $this->pparse($this->tpl, $stringMode);
        else $this->pparse($this->tpl, $stringMode);
    }
    
	//Affichage du traitement du tpl.
	function pparse($parse_name, $stringMode = false)
	{
        $this->stringMode = $stringMode;
		$file_cache_path = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(array('/', '.', '..', 'tpl', 'templates'), array('_', '', '', '', 'tpl'), $this->files[$parse_name]), '_');
        if( $stringMode )
            $file_cache_path .= '_str';
        $file_cache_path .= '.php';

		//Vérification du statut du fichier de cache associé au template.
		if( !$this->_check_cache_file($this->files[$parse_name], $file_cache_path) )
		{
			//Chargement du template.
			if( !$this->_load($parse_name) )
				return '';

			//Parse
			$this->_parse($parse_name, $stringMode);
			$this->_clean(); //On nettoie avant d'envoyer le flux.
			$this->_save($file_cache_path, $stringMode); //Enregistrement du fichier de cache.
		}
        
		include($file_cache_path);
		
        if( $this->stringMode )
            return $tplString;
	}

	// Object  cloner
	function copy()
	{
		$copy = new Template();
		
		$copy->tpl = $this->tpl;
		$copy->template = $this->template;
		$copy->files = $this->files;
		$copy->module_data_path = $this->module_data_path;
	    $copy->stringMode = $this->stringMode;
        
        $copy->_var = $this->_var; //Tableau contenant les variables de remplacement des variables simples.
        $copy->_block = $this->_block; //Tableau contenant les variables de remplacement des variables simples.
        
		return $copy;
	}
	
	
	## Private Methods ##
	//Vérification de l'existence du tpl perso, sinon tentative de chargement du tpl de base.
	function _check_file($filename)
	{
		global $CONFIG;
        
		$filename = trim($filename, '/');
		$i = strpos($filename, '/');
		$module = substr($filename, 0, $i);
        $file_name = substr($filename, strrpos($filename, '/') + 1);
		$file = trim(substr($filename, $i), '/');
		$folder = trim(substr($file, 0, strpos($file, '/')), '/');

		if( empty($module) )
        {   // Template du thème (noyau)
            return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $filename;
        }
        elseif( $module == 'admin' )
        {   // Admin
            $file_path = PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $filename;
            if( file_exists($file_path) )
                return $file_path;
            return PATH_TO_ROOT . '/admin/templates/' . $file_name;
        }
		else
		{   // Module
			if( $module == 'framework' && file_exists(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/framework/' . $file) )
				return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/framework/' . $file;
			if( $folder == 'framework' )
			{
				if( file_exists($file_path = (PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/' . $file)) )
					return $file_path;
				elseif( file_exists($file_path = (PATH_TO_ROOT . '/' . $module . '/templates/' . $file)) )
					return $file_path;
				else
					return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/' . $file;
			}
            
			//module data path
			if( !isset($this->module_data_path[$module]) )
			{
				if( is_dir(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/images') )
					$this->module_data_path[$module] = PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module;
				else
					$this->module_data_path[$module] = PATH_TO_ROOT . '/' . $module . '/templates';
			}

			if( file_exists(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/' . $file) )
				return PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/' . $file;
			else
				return PATH_TO_ROOT . '/' . $module . '/templates/' . $file;
		}
	}
	
	//Vérifie le statut du fichier en cache, il sera regénéré s'il n'existe pas ou si il est périmé.
	function _check_cache_file($tpl_path, $file_cache_path)
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
	function _load($parse_name)
	{
		if( !isset($this->files[$parse_name]) )
			return false;
			
		$this->template = @file_get_contents_emulate($this->files[$parse_name]); //Charge le contenu du fichier tpl.
		if( $this->template === false )
			die('Template->_load(): Le chargement du fichier ' . $this->files[$parse_name] . ' pour parser ' . $parse_name . ' a échoué');
		if( empty($this->template) )
			die('Template->_load(): Le fichier ' . $this->files[$parse_name] . ' pour parser ' . $parse_name . ' est vide');
			
		return true;
	}
	
	//Inclusion d'un template dans un autre.
	function _include($parse_name)
	{
 		if( isset($this->files[$parse_name]) )
        {
            if( $this->stringMode )
                return $this->pparse($parse_name, $this->stringMode);
            else
                $this->pparse($parse_name, $this->stringMode);
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
            $this->template = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, 'parse_conditionnal_blocks'), $this->template);
            $this->template = preg_replace('`# ELSE #`', '\';'."\n".'} else {'."\n".'$tplString .= \'', $this->template);
            $this->template = preg_replace('`# ENDIF #`', '\';'."\n".'}'."\n".'$tplString .= \'', $this->template);
            
            //Remplacement des includes.
            $this->template = preg_replace('`# INCLUDE ([\w]+) #`', '\';'."\n".'$tplString .= $this->_include(\'$1\');'."\n".'$tplString .= \'', $this->template);
        }
        else
        {
            // Protection des injections PHP et affichages des tags XML
            $this->template = preg_replace_callback('`\<\?(.*)\?\>`i', array($this, '_protect_from_inject('), $this->template);
            
            //Remplacement des variables simples.
            $this->template = preg_replace('`{([\w]+)}`i', '<?php echo (isset($this->_var[\'$1\']) ? $this->_var[\'$1\'] : \'\'); ?>', $this->template);
            $this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'parse_blocks_vars'), $this->template);
            
            //Parse des blocs imbriqués ou non.
            $this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'parse_blocks'), $this->template);
            $this->template = preg_replace('`# END [\w\.]+ #`', '<?php } ?>', $this->template);
            
            //Remplacement des blocs conditionnels.
            $this->template = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, 'parse_conditionnal_blocks'), $this->template);
            $this->template = preg_replace('`# ELSE #`', '<?php } else { ?>', $this->template);
            $this->template = preg_replace('`# ENDIF #`', '<?php } ?>', $this->template);
            
            //Remplacement des includes.
            $this->template = preg_replace('`# INCLUDE ([\w]+) #`', '<?php $this->_include(\'$1\'); ?>', $this->template);
        }
    }
    
    function _protect_from_inject($mask)
    {
        return '<?php echo \'<?' . str_replace(array('\\', '\''), array('\\\\', '\\\''), trim($mask[1])) . '?>\'; ?>';
    }

    
	//Remplacement des variables de type bloc.
	function _parse_blocks_vars($blocks)
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
	function _parse_blocks($blocks)
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
	function _parse_conditionnal_blocks($blocks)
	{
		if( isset($blocks[2]) )
		{
            $not = ($blocks[1] == 'NOT ' ? '!' : '');
			if( strpos($blocks[2], '.') !== false ) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[2]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);

                if( $this->stringMode )
                    return '\';'."\n".'if( isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\'] ) {'."\n".'$tplString .= \'';
                else
				    return '<?php if( isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\'] ) { ?>';
			}
			else
            {
                if( $this->stringMode )
                    return '\';'."\n".'if( isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\'] ) {'."\n".'$tplString .= \'';
                else
                    return '<?php if( isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\'] ) { ?>';
		    }
        }
		return '';
	}
	
	//Nettoyage des commentaires, et blocs et variables non utilisés.
	function _clean()
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
	function _save($file_cache_path, $stringMode = false)
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