<?php
/**
*
* @package Statsomatic\Data
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class Statsomatic_Data_Definitions
{
    protected $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function lookup($provider, $variable)
    {
        if (isset($this->providers[$provider][$variable]))
        {
            return new Statsomatic_Variable(
                $provider,
                $variable,
                $this->providers[$provider][$variable]['type'],
                $this->providers[$provider][$variable]['isArray'],
                ($this->providers[$provider][$variable]['main'] == true) ? Statsomatic_Variable::MAIN : Statsomatic_Variable::DETAILS
            );
        }

        throw new Exception(sprintf("Unknown variable '%s' '%s'.", $provider, $variable));
    }
}
