<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 6.0 - 2024 06 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ChartDataset
{
    protected $colors = [
        '224, 118, 27',
        '48, 149, 53', 
        '254, 249, 52', 
        '102, 133, 237', 
        '204, 42, 38', 
        '53, 144, 189', 
        '102, 102, 153', 
        '236, 230, 208', 
        '213, 171, 1', 
        '182, 0, 51', 
        '193, 73, 0', 
        '25, 119, 128', 
        '182, 181, 177', 
        '102, 133, 237'
    ];

    public bool $use_many_colors = true;
    public array $data_labels = [];
    public array $data_values = [];
    public array $data_colors = [];
    public string $color_pattern = 'rgba(%s, 0.7)';
    public string $label;
    public string|false $type = false;
    public array $options = [];

    /** Create a new Dataset for Chart */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * Hydrate a dataset by giving an array with key => value
     * eg : ['Firefox' => 5, 'Chrome' => 2]
     */
    public function set_datas(array $data): void
    {
        $i = 0;
        if ($this->use_many_colors)
        {
            $icolor = 0;
        }
        else
        {
            $icolor = rand(0, count($this->colors) -1);
        }
        foreach ($data as $key => $value) 
        {
            if ($icolor == count($this->colors))
            {
                $icolor = 0;
            }
            $this->data_labels[$i] = $key;
            $this->data_values[$i] = $value;
            $this->data_colors[$i] = $this->colors[$icolor];
            $i++;
            if ($this->use_many_colors)
            {
                $icolor++;
            }
        }
    }

    /**
     * Set colors by defining an array of colors in RGB
     * eg ['224, 118, 27','48, 149, 53']
     * Have to set this before set_datas
     */
    public function set_colors(array $colors): void
    {
        $this->colors = $colors;
    }

    /**
     * Get an item color by its key name
     * @return string color like (rgb(xxx))
     */
    public function get_color_label(string $label): string 
    {
        $key = array_search($label, $this->data_labels);
        return sprintf($this->color_pattern, $this->data_colors[$key]);
    }

    /**
     * Build an array with all data patterned colors ready to be used
     * @return array
     */
    public function get_colors(): array
    {
        $colors = [];
        foreach ($this->data_colors as $color)
        {
            $colors[] = sprintf($this->color_pattern, $color);
        }
        return $colors;
    }

    /**
     * Set the pattern color
     * eg : 'rgb(%s)'
     * @param string $pattern
     */
    public function set_color_pattern(string $pattern): void
    {
        $this->color_pattern = $pattern;
    }

}