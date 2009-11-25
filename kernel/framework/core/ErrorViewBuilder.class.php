<?php
/*##################################################
 *                           ErrorViewBuilder.class.php
 *                            -------------------
 *   begin                : October 29 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 ###################################################*/



class ErrorViewBuilder
{
	/**
	 * @var string[string]
	 */
	private $lang;

	/**
	 * @var View
	 */
	private $view;

	public function build($level = null, $title = null, $code = null, $message = null,
	$correction_link = null, $correction_link_name = null, $exception = null)
	{	
		$this->init_view();

		$this->view->assign_vars(array(
            'TITLE' => $this->get_title($title),
            'CODE' => $this->get_code($code, $exception),
			'MESSAGE' => $this->get_message($message, $exception),
			'LINK_NAME' => $this->get_correction_link_name($correction_link_name),
			'U_LINK' => $this->get_correction_link($correction_link),
			'LEVEL' => $this->get_level_img_name($level)
		));

		return $this->view;
	}

	private function init_view()
	{
		$this->view = new View('member/error_controller.tpl');
		$this->lang = LangLoader::get(get_class());
		$this->view->add_lang($this->lang);
	}

	private function get_title($title)
	{
		if (empty($title))
		{
			$title= $this->lang['error'];
		}
		return $title;
	}

	private function get_message($message, $exception)
	{
		if (empty($message))
		{
			if ($exception !== null && DEBUG)
			{
				$message = htmlspecialchars($exception->getMessage()) . '<br /><br /><i>' .
				$exception->getFile() . ':' . $exception->getLine() .
				'</i><div class="spacer">&nbsp;</div>' .
				Debug::get_stacktrace_as_string(0, $exception);
			}
			else
			{
				$message = htmlspecialchars($this->lang['unexpected_error_occurs']);
			}
		}
		return $message;
	}

	private function get_code($code, $exception)
	{
		if (empty($code))
		{
			if ($exception !== null && DEBUG)
			{
				$code = $exception->getCode();
			}
		}
		return $code;
	}

	private function get_correction_link($correction_link)
	{
		if (empty($correction_link))
		{
			$correction_link = 'javascript:history.back(1);';
		}
		return $correction_link;
	}

	private function get_correction_link_name($correction_link_name)
	{
		if (empty($correction_link_name))
		{
			$correction_link_name = $this->lang['back'];
		}
		return $correction_link_name;
	}

	private function get_level_img_name($level)
	{
		if (empty($level))
		{
			$level = E_UNKNOWN;
		}

		$level_img = 'question';
		switch ($level)
		{
			case E_USER_NOTICE:
			case E_NOTICE:
			case E_STRICT:
				$level_img = 'notice';
				break;
				//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				$level_img = 'important';
				break;
				//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:
				$level_img = 'stop';
				break;
				//Erreur inconnue.
			case E_UNKNOWN:
			default:
				$level_img = 'question';
		}
		return $level_img;
	}
}
?>