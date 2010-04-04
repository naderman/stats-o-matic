<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Graph_PieChart extends Statsomatic_Graph
{
    public function getWidth()
    {
        return 800;
    }

    public function getHeight()
    {
        return 400;
    }

    public function prepareQuery()
    {
        $this->query->limit(30);
    }

    public function createGraph()
    {
        $this->graph = new ezcGraphPieChart();
    }

    public function setData()
    {
        $title = $this->graph->title->title;

        $this->graph->data[$title] = new ezcGraphDatabaseDataSet($this->stmt, array('value2', 'value1'));
    }
}
