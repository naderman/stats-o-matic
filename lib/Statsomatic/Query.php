<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Query extends ezcQuerySelect
{
    private $query;
    private $currentMain = 0;
    private $currentDetails = 0;

    public $expr;
    public $db;

    public function __construct(ezcQuerySelect $q)
    {
        $this->query = $q;
        $this->db = $q->db;
        $this->expr = $q->expr;
    }

    public function nextMainAlias()
    {
        $this->currentMain++;
        return $this->alias('statistics_main', $this->currentMainAlias());
    }

    public function currentMainAlias()
    {
        return 'm' . $this->currentMain;
    }

    public function nextDetailsAlias()
    {
        $this->currentDetails++;
        return $this->alias('statistics_details', $this->currentDetailsAlias());
    }

    public function currentDetailsAlias()
    {
        return 'd' . $this->currentDetails;
    }

    public function mainColumn($column)
    {
        return $this->currentMainAlias() . '.' . $column;
    }

    public function detailColumn($column)
    {
        return $this->currentDetailsAlias() . '.' . $column;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->query, $name), $arguments);
    }
}
