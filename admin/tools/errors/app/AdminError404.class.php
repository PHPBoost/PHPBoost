<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 14
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminError404 extends BusinessObject
{
    private ?int $id = null;
    private string $requested_url;
    private string $from_url;
    private int $times = 1;

    public function __construct(string $requested_url = '', string $from_url = '')
    {
        $this->requested_url = $requested_url;
        $this->from_url = $from_url;
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_requested_url(): string
    {
        return $this->requested_url;
    }

    public function get_from_url(): string
    {
        return $this->from_url;
    }

	public function get_times(): int
	{
		return $this->times;
	}

    public function set_id(?int $value): void
    {
        $this->id = $value;
    }

    public function set_requested_url(string $value): void
    {
        $this->requested_url = $value;
    }

    public function set_from_url(string $value): void
    {
        $this->from_url = $value;
    }

	public function set_times(int $value): void
	{
		$this->times = $value;
	}

	public function increment(): void
	{
		$this->times++;
	}
}
?>
