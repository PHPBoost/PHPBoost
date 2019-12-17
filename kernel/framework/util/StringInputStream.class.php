<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2010 07 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class StringInputStream
{
	private $stream;
	private $index = -1;
	private $length;

	public function __construct($string)
	{
		$this->stream = $string;
		$this->length = TextHelper::strlen($this->stream);
	}

	public function has_next()
	{
		return $this->index < ($this->length - 1);
	}

	public function next()
	{
		$this->index++;
		return $this->get_current();
	}

	public function get_current()
	{
		if ($this->index < 0)
		{
			throw new OutOfBoundsException('out of stream', 0);
		}
		return $this->stream[$this->index];
	}

	public function get_next()
	{
		if (!$this->has_next())
		{
			throw new OutOfBoundsException('end of stream', 0);
		}
		return $this->stream[$this->index + 1];
	}

	public function get_previous()
	{
		if ($this->index <= 0)
		{
			throw new OutOfBoundsException('beginning of stream', 0);
		}
		return $this->stream[$this->index - 1];
	}

	public function assert_next($pattern, $options = '', array &$matches = null)
	{
		$subject = TextHelper::substr($this->stream, $this->index + 1);
		return preg_match('`^(?:' . $pattern . ')`u' . $options, $subject, $matches);
	}

	public function consume_next($pattern, $options = '', array &$matches = null)
	{
		if ($this->assert_next($pattern, $options, $matches))
		{
			$this->move(TextHelper::strlen($matches[0]));
			return true;
		}
		return false;
	}

	public function move($delta)
	{
		$new_index = $this->index + $delta;
		$this->seek($new_index);
	}

    public function safe_move($delta)
    {
        $new_index = $this->index + $delta;
        $safe_index = max(0, min($new_index, $this->length - 1));
        $this->seek($safe_index);
    }

	public function tell()
	{
		return $this->index;
	}

	public function seek($new_index)
	{
		if (!($new_index >= -1 && $new_index < $this->length))
		{
			throw new OutOfBoundsException('new index ' . $new_index .
				' is out of bounds (size: ' . $this->length . ')', 0);
		}
		$this->index = $new_index;
	}

	public function to_string($delta = 0, $max_length = 50)
	{
		$old_index = $this->index;
		$this->safe_move($delta);
		$str = TextHelper::substr($this->stream, $this->index);
		$this->seek($old_index);
		if ($max_length > 0)
		{
			return TextHelper::substr($str, 0, $max_length);
		}
		return $str;
	}

	public function entire_string()
	{
		return $this->stream;
	}
}
?>
