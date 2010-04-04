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
    protected $providers = array(
        false => array(
            'install_id' => array(
                'type' => 'varchar',
                'isArray' => false,
                'main' => true,
            ),
            'entry_id' => array(
                'type' => 'varchar',
                'isArray' => false,
                'main' => true,
            ),
            'timestamp' => array(
                'type' => 'int',
                'isArray' => false,
                'main' => true,
            ),
        ),
    );

    public function __construct(array $providers)
    {
        foreach ($providers as $key => $provider)
        {
            if (is_array($provider))
            {
                $this->providers[$key] = $provider;
            }
            else
            {
                $this->providers[$provider] = include('Statsomatic/Data/Provider' . $provider . '.php');
            }
        }
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
