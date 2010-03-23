<?php
/**
*
* @package viewer
* @copyright (c) 2010 Nils Adermann
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class ezcClassLoader
{
    private $component;
    private $classLocations;

    /**
    * Creates a new ezcClassLoader for the given component.
    *
    * @param string $component The component to load from.
    */
    public function __construct($component)
    {
        $this->component = $component;
        $classLocations = include($this->getPath() . strtolower($component) . '_autoload.php');

        foreach ($classLocations as $class => $path)
        {
            $this->classLocations[$class] = substr($path, strlen($component) + 1); // strip component name from path
        }
    }

    /**
    * Calculate this component's include path.
    *
    * @return The path to the component's files.
    */
    public function getPath()
    {
        return 'ezc-' . strtolower($this->component) . '/src/';
    }

    /**
    * Installs this class loader on the SPL autoload stack.
    */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
    * Uninstalls this class loader from the SPL autoloader stack.
    */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
    * Loads the given class or interface.
    *
    * @param string $className The name of the class to load.
    */
    public function loadClass($className)
    {
        if (isset($this->classLocations[$className]))
        {
            require $this->getPath() . $this->classLocations[$className];
        }
    }
}
