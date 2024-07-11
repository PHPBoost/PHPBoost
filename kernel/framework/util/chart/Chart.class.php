<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 6.0 - 2024 06 29
 * @see         https://www.chartjs.org/docs/latest/
*/

abstract class Chart 
{

    public $name;

    protected $type;

    protected $datasets = [];

    protected $options = [];

    /**
     * Create a chart
     * @param string $name name of chart in html
     */
    public function __construct(string $name) 
    {
        $this->name = $name;
    }

    /**
     * Add a dataset to the chart
     * @param ChartDataset $dataset
     */
    public function add_dataset(ChartDataset $dataset)
    {
        $this->datasets[] = $dataset;
    }

    /**
     * Define options for the chart
     * @param array $options associative array
     */
    public function set_options(array $options):void
    {
        $this->options = $options;
    }

    /**
     * Get generated HTML to display chart
     * @return string html
     */
    public function get_html():string
    {
        $template = new FileTemplate('framework/util/chart.tpl');
        $template->put_all([
            'DATA' => $this->get_datas(),
            'OPTIONS' => $this->get_options(),
            'NAME' => $this->name,
            'TYPE' => $this->type,
            'C_OPTIONS' => !empty($this->options)
        ]);
        return $template->render();
    }

    protected function get_datas():string
    {
        $formatted_datasets = [];
        $i = 0;
        $nbr_items = count($this->datasets);
        foreach ($this->datasets as $dataset)
        {
            $formatted_datasets[$i]['label'] = $dataset->label;
            $formatted_datasets[$i]['data'] = $dataset->data_values;
            $formatted_datasets[$i]['backgroundColor'] = $dataset->get_colors();
            $formatted_datasets[$i]['borderColor'] = $dataset->get_colors();
            $formatted_datasets[$i]['order'] = $nbr_items - $i;
            if ($dataset->type !== false)
            {
                $formatted_datasets[$i]['type'] = $dataset->type;
            }
            $i++;
        }
        return json_encode([
            'labels' => $this->datasets[0]->data_labels,
            'datasets' => $formatted_datasets,
        ]);
    }

    protected function get_options():string
    {
        return json_encode($this->options);
    }

    protected function get_labels():string
    {
        return json_encode($this->datasets[0]->data_labels);
    }
}