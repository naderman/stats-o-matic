<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

abstract class Statsomatic_Graph implements Statsomatic_GraphInterface
{
    protected $query;
    protected $stmt = null;
    protected $graph = null;

    public function __construct(Statsomatic_Query $query)
    {
        $this->query = $query;
    }

    public function render($filename)
    {
        $this->createGraph();
        $this->graph->title = 'The Title';

        $this->prepareQuery();

        $this->stmt = $this->query->prepare();
        $this->stmt->execute();

        $this->setData();

        $this->graph->render($this->getWidth(), $this->getHeight(), $filename);
    }

    public function prepareQuery()
    {
    }

    abstract public function getWidth();
    abstract public function getHeight();
    abstract public function createGraph();
    abstract public function setData();
}
