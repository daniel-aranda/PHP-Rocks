<?php
namespace PHPRocks\Test;
use PHPRocks\Configuration;
use PHPRocks\DependencyManager;
use PHPRocks\Environment;
use PHPRocks\Util\OptionableArray;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class ConfigurationTest extends Base
{

    /**
     * @var \PHPRocks\Configuration
     */
    private $config;

    /**
     * @var string
     */
    private $path;

    protected function setUp()
    {
        $this->path = $this->getFixturesDirectory() . 'configuration_test.json';
        $this->config = Configuration::factory( $this->path, Environment::factory(true) );
    }

    protected function tearDown()
    {
    }

    public function testFactory()
    {
        $this->path = $this->getFixturesDirectory() . 'configuration_test.json';
        $instance = Configuration::factory( $this->path );

        $this->assertInstanceOf('\PHPRocks\Configuration', $instance);
    }

    public function testInstance()
    {
        $this->path = $this->getFixturesDirectory() . 'configuration_test.json';
        $instance = Configuration::instance( $this->path );

        $this->assertInstanceOf('\PHPRocks\Configuration', $instance);
    }

    public function testDependencyFactory()
    {
        $instance = DependencyManager::get('\PHPRocks\Configuration', [$this->path]);
        $this->assertInstanceOf('\PHPRocks\Configuration', $instance);
        DependencyManager::reset();
    }

    public function testGetValue(){

        $this->assertSame('value', $this->config->get('system.test.item'));
        $this->assertSame(8, $this->config->get('some'));

    }

    public function testSetValue(){

        $this->config->set('nombre.linda', 'lili');
        $this->assertSame('lili', $this->config->get('nombre.linda'));

        $this->config->set('linda', 'lili');
        $this->assertSame('lili', $this->config->get('linda'));

    }

    public function testEnvironment(){

        $this->assertSame($this->config->environment(), 'unit_test');

    }

    public function testGetValueNotFound(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\ValueNotSet');
        $this->config->get('random');

    }

    public function testEnvironmentIsSandbox(){

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.daniel-aranda.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $this->path,
            $custom_environment
        );

        $this->assertSame($config->environment(), 'sandbox');

    }

    public function testValueNoEnvironmentsSet(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\ValueNotSet');

        $path = $this->getFixturesDirectory() . 'empty_configuration.json';
        $config = new Configuration($path, Environment::factory(true));

        $config->environment();

    }

    public function testInvalidPath(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\InvalidPath');

        $config = new Configuration('randompath', Environment::factory(true));

        $config->environment();

    }

    public function testEnvironmentNotFound(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\EnvironmentNotFound');

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.random.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $this->path,
            $custom_environment
        );

        $config->environment();

    }

    public function testInvalidEnvironment(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\InvalidEnvironment');

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.random.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $this->path,
            $custom_environment
        );

        $config->set('environments.random', ['sandbox.random.com']);

        $config->environment();

    }

    public function testGetPerEnvironment(){
        $this->assertSame(1002, $this->config->getPerEnvironment('specific'));
        $this->assertSame(1001, $this->config->getPerEnvironment('specific', 'sandbox'));
        $this->assertSame(1000, $this->config->getPerEnvironment('specific', 'development'));
    }

    public function testGetPerInvalidEnvironment()
    {
        $this->setExpectedException('PHPRocks\Exception\Configuration\InvalidEnvironment');

        $this->config->getPerEnvironment('specific', 'random');
    }

}
