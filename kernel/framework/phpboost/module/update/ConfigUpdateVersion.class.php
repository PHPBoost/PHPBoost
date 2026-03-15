<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2026 03 15
 * @since       PHPBoost 3.0 - 2012 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class ConfigUpdateVersion implements UpdateVersion
{
	protected static $module_id;

	protected $delete_old_config;
	protected $config_name;
	protected $querier;

	protected $config_parameters_to_modify = array();

	public function __construct($module_id, $delete_old_config = false, $config_name = '')
	{
		self::$module_id = $module_id;
		$this->delete_old_config = $delete_old_config;
		$this->config_name = $config_name ? $config_name : self::$module_id . '-config';
		$this->querier = PersistenceContext::get_querier();
	}

	public function get_module_id()
	{
		return self::$module_id;
	}

	public function get_config_name()
	{
		return $this->config_name;
	}

	public function execute()
	{
		try {
			if ($this->build_new_config())
			{
				if ($this->delete_old_config)
				{
					$this->delete_old_config();
				}
			}
		} catch (RowNotFoundException $e) {}
	}

	protected function get_old_config($serialize = true)
    {
        $old_config = array();
        try {
            $old_config = $this->querier->get_column_value(DB_TABLE_CONFIGS, 'value', 
                'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
        } catch (RowNotFoundException $e) {}

        if ($serialize)
        {
            return self::fix_and_unserialize($old_config);
        }
        return $old_config;
    }

    /**
     * Désérialise une chaîne PHP potentiellement corrompue par mbstring.func_overload.
     * Lorsque mbstring.func_overload = 2 ou 7, PHP's serialize() utilise mb_strlen()
     * (nombre de caractères) au lieu de strlen() (nombre d'octets) pour les valeurs s:N.
     * Ce parseur corrige les longueurs en testant d'abord N comme octet-count,
     * puis comme char-count si la vérification échoue.
     */
    private static function fix_and_unserialize($data)
    {
        if (empty($data) || !is_string($data))
            return $data;

        $out  = '';
        $i    = 0;
        $len  = strlen($data);

        while ($i < $len)
        {
            // Détecte le pattern s:N:"
            if ($i + 2 < $len && $data[$i] === 's' && $data[$i + 1] === ':')
            {
                // Lire le nombre N
                $colon = strpos($data, ':"', $i + 2);
                if ($colon !== false && ctype_digit(substr($data, $i + 2, $colon - $i - 2)))
                {
                    $n             = (int) substr($data, $i + 2, $colon - $i - 2);
                    $content_start = $colon + 2;          // position du 1er octet du contenu

                    // === Cas 1 : N est déjà un octet-count correct ===
                    if ($content_start + $n + 2 <= $len
                        && $data[$content_start + $n]     === '"'
                        && $data[$content_start + $n + 1] === ';')
                    {
                        // Pas de correction nécessaire, copier tel quel
                        $out .= substr($data, $i, $content_start + $n + 2 - $i);
                        $i    = $content_start + $n + 2;
                        continue;
                    }

                    // === Cas 2 : N est un char-count (mbstring.func_overload) ===
                    // Extraire N caractères UTF-8 depuis content_start
                    $char_offset   = mb_strlen(substr($data, 0, $content_start), 'UTF-8');
                    $content_chars = mb_substr($data, $char_offset, $n, 'UTF-8');
                    $byte_len      = strlen($content_chars);

                    if ($content_start + $byte_len + 2 <= $len
                        && $data[$content_start + $byte_len]     === '"'
                        && $data[$content_start + $byte_len + 1] === ';')
                    {
                        // Corriger : écrire s:byte_len:"content";
                        $out .= 's:' . $byte_len . ':"' . $content_chars . '";';
                        $i    = $content_start + $byte_len + 2;
                        continue;
                    }
                }
            }

            // Octet ordinaire : copier sans modification
            $out .= $data[$i];
            $i++;
        }

        $result = @unserialize($out);

        // Dernier recours : tenter la désérialisation de la chaîne originale
        if ($result === false && $data !== 'b:0;')
            $result = @unserialize($data);

        return $result;
    }

	protected function save_new_config($name, ConfigData $data)
	{
		return $this->querier->inject('UPDATE ' . DB_TABLE_CONFIGS . ' SET value = :value WHERE name = :config_name', array('value' => TextHelper::serialize($data), 'config_name' => $name));
	}

	protected function build_new_config()
	{
		return $this->modify_config_parameters();
	}

	protected function delete_old_config()
	{
		$this->querier->delete(DB_TABLE_CONFIGS, 'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
	}

	/**
	 * Updates config parameters.
	 */
	protected function modify_config_parameters()
	{
		$configuration_class_name = ucfirst(self::$module_id) . 'Config';
		$old_config = $this->get_old_config();

		if (ClassLoader::is_class_registered_and_valid($configuration_class_name) && !empty($old_config))
		{
			$config = $configuration_class_name::load(self::$module_id);
			$modified_properties = array();
			foreach ($this->config_parameters_to_modify as $old_name => $new_name)
			{
				$property = '';
				
				try {
					$property = $old_config->get_property($old_name);
				} catch (PropertyNotFoundException $e) {}
				if ($property)
				{
					if (!is_array($new_name))
					{
						$set_new_property = 'set_' . $new_name;
						$config->$set_new_property($property);
						$modified_properties[] = $new_name;
					}
					else
					{
						$set_new_property = 'set_' . $new_name['parameter_name'];
						
						if (isset($new_name['values']))
						{
							foreach ($new_name['values'] as $old_value_name => $new_value_name)
							{
								if ($property == $old_value_name)
									$config->$set_new_property($new_value_name);
							}
							$modified_properties[] = $new_name['parameter_name'];
						}
						else if (isset($new_name['value']))
						{
							$config->$set_new_property($new_name['value']);
							$modified_properties[] = $new_name['parameter_name'];
						}
					}
				}
			}
			
			$configuration_class = new ReflectionClass($configuration_class_name);
			
			foreach (array_diff($configuration_class->getConstants(), $modified_properties) as $parameter)
			{
				if (is_string($parameter))
				{
					$property = '';

					try {
						$property = $old_config->get_property($parameter);
					} catch (PropertyNotFoundException $e) {}
					if ($property && $configuration_class->hasMethod('set_' . $parameter))
					{
						$set_new_property = 'set_' . $parameter;
						$config->$set_new_property($property);
					}
				}
			}

			$configuration_class_name::save(self::$module_id);

			return true;
		}
		return false;
	}

	protected function get_parsed_old_content($module_config, $parameter)
	{
		$old_config = $this->get_old_config();
		if ($old_config && (ModulesManager::is_module_installed(self::$module_id) && ModulesManager::is_module_activated(self::$module_id)))
		{
			$config = $module_config::load();
			$unparser = new OldBBCodeUnparser();
			$parser = new BBCodeParser();
			$root_description = $old_config->get_property($parameter);

			$getter = 'get_'.$parameter;
			$setter = 'set_'.$parameter;
			$unparser->set_content($root_description);
			$unparser->parse();
			$parser->set_content($unparser->get_content());
			$parser->parse();

			if ($parser->get_content() != $root_description)
			{
				$config->$setter($parser->get_content());
				return $config->$getter();
			}
		}
	}
}
?>
