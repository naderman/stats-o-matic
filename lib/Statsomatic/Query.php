<?php
/**
*
* @package Statsomatic
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Query
{
    protected $query;

    protected $fromArgs = array();
    protected $leftJoins = array();

    protected $currentMain = 0;
    protected $currentDetails = 0;
    protected $currentValue = 0;

    public $expr;
    public $db;

    public function __construct(ezcQuerySelect $q, PDO $db)
    {
        $this->query = $q;
        $this->db = $db;
        $this->expr = $q->expr;
    }

    public function from()
    {
        $this->fromArgs = func_get_args();

        return $this;
    }

    public function addLeftJoin($table, $exprOrId1, $id2 = null)
    {
        $this->leftJoins[] = array($table, $exprOrId1, $id2);
    }

    public function getQuery()
    {
        if (sizeof($this->fromArgs))
        {
            call_user_func_array(array($this->query, 'from'), $this->fromArgs);
        }

        foreach ($this->leftJoins as $leftJoin)
        {
            if ($leftJoin[2] !== null)
            {
                $this->leftJoin($leftJoin[0], $leftJoin[1], $leftJoin[2]);
            }
            else
            {
                $this->leftJoin($leftJoin[0], $leftJoin[1]);
            }
        }

        $this->fromArgs = array();
        $this->leftJoins = array();

        return $this->query->getQuery();
    }

    public function nextValueAlias($expression)
    {
        $this->currentValue++;
        return $this->alias($expression, 'value' . $this->currentValue);
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
        $result = call_user_func_array(array($this->query, $name), $arguments);

        // make sure the fluid interface still works so return $this if necessary
        if ($result === $this->query)
        {
            return $this;
        }

        return $result;
    }
}
