<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mx.koder@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 23
 * @since       PHPBoost 6.0 - 2024 06 14
*/

abstract class StatsChart 
{
    public $colors = [
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

    public $name;

    public $label;

    protected $type;

    protected $dataset_labels = [];

    protected $dataset_datas = [];

    protected $dataset_colors = [];

    protected $additional_dataset_options = ['borderWidth' => 1];

    protected $additional_options = [];

    protected $additionals_datasets = [];

    public function __construct(string $name, string $label) 
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Add the main dataset as an array like
     * ['firefox' => 5, 'chrome' => 4]
     */
    public function set_dataset(array $data):void
    {   
        $i = 0;
        $icolor = 0;
        foreach ($data as $key => $value) 
        {
            if ($icolor == count($this->colors))
            {
                $icolor = 0;
            }
            $this->dataset_labels[$i] = $key;
            $this->dataset_datas[$i] = $value;
            $this->dataset_colors[$i] = $this->colors[$icolor];
            $i++;
            $icolor++;
        }
    }

    /**
     * Get an item color by its key name
     * @return string color like (rgb(xxx))
     */
    public function get_color_label(string $label):string 
    {
        $key = array_search($label, $this->dataset_labels);
        return 'rgba(' . $this->dataset_colors[$key] . ', 0.6)';
    }

    public function __tostring():string
    {
        $t = '<div class="chart-container chart-'. $this->type .'"><canvas id="' . $this->name . '"></canvas></div>';
        $t .= "<script>
                    const ctx" . $this->name ." = document.getElementById('" . $this->name ."');
                    new Chart(ctx" . $this->name .", {
                        type: '" . $this->type . "',
                        data: ".json_encode($this->prepare_to_export()).",
                        options: ".json_encode($this->get_additionnals_options())."
                    });
                </script>";
        return $t;
    }

    protected function prepare_to_export():array 
    {
        $data = [];
        $data['labels'] = $this->dataset_labels;
        $data['datasets'] = $this->get_datasets();
        return $data;
    }

    /**
     * Merge all datasets and return them as an array
     */
    protected function get_datasets():array
    {
        $datasets = [];
        $dataset = [
            'label' => $this->label,
            'data'  => $this->dataset_datas
        ];
        $colors = $this->get_script_colors();
        if (count($colors) > 1)
        {
            foreach ($colors as $color)
            {
                $dataset = array_merge($dataset, $color);
            }
        }
        else 
        {
            $dataset = array_merge($dataset, $colors);
        }
        $datasets[] = array_merge($dataset, $this->get_additionnals_options_dataset());
        return array_merge(array_values($datasets), array_values($this->additionals_datasets));
    }

    protected function get_script_colors():array
    {
        $bg_colors = [];
        $border_colors = [];
        foreach ($this->dataset_colors as $color)
        {
            $bg_colors[] = 'rgba(' . $color . ', 0.6)';
            $border_colors[] = 'rgb(' . $color . ')';
        }
        return [
            ['backgroundColor' => $bg_colors],
            ['borderColor' => $border_colors]
        ];
    }
    
    protected function get_additionnals_options_dataset():array
    {
        return $this->additional_dataset_options;
    }

    protected function get_additionnals_options():array
    {
        return $this->additional_options;
    }
}