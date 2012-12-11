<?php
/**
 * ConfigReader file
 *
 * PHP 5.2
 *
 * ConfigReader is a rapid configuration loader for PHP like CakePHP\PhpReader.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 * @since   CakePHP(tm) v 2.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/** ConfigureException */
require dirname(__FILE__).DIRECTORY_SEPARATOR.'ConfigureException.php';

/**
 * Load configuration values from files containing simple PHP arrays.
 *
 * should define a `$config` variable, 
 * that contains all of the configuration data contained in the file.
 */
class ConfigReader {

    /**
     * The path this reader finds files on.
     *
     * @var string
     */
    private $path = null;

    /**
     * Constructor
     *
     * @param string $path The path to read config files from.
     */
    public function __construct($path) {
        $this->path = $path;
    }

    /**
     * Read a config file and return its contents.
     *
     * @param string $key The identifier to read from.
     * @return array Parsed configuration values.
     * @throws ConfigureException 
     *     when files don't exist or they don't contain `$config`.
     *     Or when files contain '..' as this could lead to abusive reads.
     */
    public function read($key) {
        if (strpos($key, '..') !== false) {
            $message = 'Cannot load configuration files with ../ in them.';
            throw new ConfigureException($message);
        }

        $fileName = $key;
        if (substr($fileName, -4) === '.php') {
            $fileName = substr($fileName, 0, -4);
        }
        $fileName .= '.php';

        $saveDirectory = $this->path;
        if (substr($saveDirectory, -1) === DIRECTORY_SEPARATOR) {
            $saveDirectory = substr($saveDirectory, 0, -1);
        }

        $uri = $saveDirectory.DIRECTORY_SEPARATOR.$fileName;
        if (!is_file($uri)) {
            $message = "Could not load configuration file: {$uri}";
            throw new ConfigureException($message);
        }

        include $uri;
        if (!isset($config)) {
            $message = "No variable config found in {$uri}";
            throw new ConfigureException($message);
        }

        return $config;
    }
}