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
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/** ConfigReader */
require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'ConfigReader.php';

class ConfigReaderTest extends PHPUnit_Framework_TestCase {

    /**
     * Setup.
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->path = dirname(__FILE__).DIRECTORY_SEPARATOR.'Config';
    }

    /**
     * Test reading files.
     *
     * @return void
     */
    public function testRead() {
        $reader = new ConfigReader($this->path);
        $values = $reader->read('var_test');
        $this->assertEquals('value', $values['Read']);
        $this->assertEquals('buried', $values['Deep']['Deeper']['Deepest']);

        $values = $reader->read('var_test.php');
        $this->assertEquals('value', $values['Read']);
    }

    /**
     * Test an exception is thrown by reading files that exist without .php extension.
     *
     * @expectedException ConfigureException
     * @return void
     */
    public function testReadWithExistentFileWithoutExtension() {
        $reader = new ConfigReader($this->path);
        $reader->read('no_php_extension');
    }

    /**
     * Test an exception is thrown by reading files that don't exist.
     *
     * @expectedException ConfigureException
     * @return void
     */
    public function testReadWithNonExistentFile() {
        $reader = new ConfigReader($this->path);
        $reader->read('fake_values');
    }

    /**
     * Test reading an empty file.
     *
     * @expectedException ConfigureException
     * @return void
     */
    public function testReadEmptyFile() {
        $reader = new ConfigReader($this->path);
        $reader->read('empty');
    }

    /**
     * Test reading keys with ../ doesn't work.
     *
     * @expectedException ConfigureException
     * @return void
     */
    public function testReadWithDots() {
        $reader = new ConfigReader($this->path);
        $reader->read('../empty');
    }
}
