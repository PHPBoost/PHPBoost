<?php
/*##################################################
 *                           CLICommandsList.class.php
 *                            -------------------
 *   begin                : February 07, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class CLICommandsList implements CLICommands
{
	private $commands = array();

	public function __construct(array $commands)
	{
		$this->commands = $commands;
	}

	public function get_commands()
	{
		return array_keys($this->commands);
	}
    
    public function get_short_description($cmd)
    {
        $command = $this->get_command($cmd);
        return $command->short_description();
    }
    
    public function help($cmd, array $args)
    {
        $command = $this->get_command($cmd);
        $command->help($args);
    }
    
    public function execute($cmd, array $args)
    {
        $command = $this->get_command($cmd);
    	$command->execute($args);
    }
    
    /**
     * @param string $command the command name
     * @return CLICommand
     */
    private function get_command($command)
    {
    	if (!array_key_exists($command, $this->commands))
    	{
    		throw new CommandNotFoundException($command);
    	}
    	return new $this->commands[$command]();
    }
}
?>