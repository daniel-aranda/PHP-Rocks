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

    protected function setUp()
    {
        $path = $this->getFixturesDirectory() . 'configuration_test.json';
        $this->config = Configuration::factory( $path );
    }

    protected function tearDown()
    {
    }

    public function testFactory()
    {
        $path = $this->getFixturesDirectory() . 'configuration_test.json';
        $instance = Configuration::factory( $path );

        $this->assertInstanceOf('\PHPRocks\Configuration', $instance);
    }

    public function testDependencyFactory()
    {
        $instance = DependencyManager::get('\PHPRocks\Configuration');
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

        $path = $this->getFixturesDirectory() . 'configuration_test.json';

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.daniel-aranda.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $path,
            $custom_environment
        );

        $this->assertSame($config->environment(), 'sandbox');

    }

    public function testValueNoEnvironmentsSet(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\ValueNotSet');

        $data = [];

        $config = new Configuration($data, Environment::factory());

        $config->environment();

    }

    public function testEnvironmentNotFound(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\EnvironmentNotFound');

        $path = $this->getFixturesDirectory() . 'configuration_test.json';

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.random.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $path,
            $custom_environment
        );

        $config->environment();

    }

    public function testInvalidEnvironment(){

        $this->setExpectedException('PHPRocks\Exception\Configuration\InvalidEnvironment');

        $path = $this->getFixturesDirectory() . 'configuration_test.json';

        $custom_environment = new Environment(
            new OptionableArray([
                'SERVER_NAME' => 'sandbox.random.com'
            ]),
            false,
            null
        );

        $config = Configuration::factory(
            $path,
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
/*

    public function testGetPerEnvironment(){

        $data = [
            "environments" => [
                "sandbox" => [
                    "sandbox.dealerx.com",
                    "localhost"
                ],
                "unit_test" => [
                    "unit_test"
                ]
            ],
            "db" => [
                "default" => [
                    "data_source_name" => "firebase"
                ],
                "production" => [
                    "data_source_name" => "mysql"
                ],
                "unit_test" => [
                    "data_source_name" => "sqlite:memory"
                ]
            ]
        ];

        $this->config = new RESTful_Config($data);

        $db = $this->config->get_per_environment('db');

        $this->assertSame(['data_source_name' => 'sqlite:memory'], $db);
    }

    public function testGetPerEnvironmentDefault(){

        $data = [
            "environments" => [
                "sandbox" => [
                    "sandbox.dealerx.com",
                    "localhost"
                ],
                "unit_test" => [
                    "unit_test"
                ]
            ],
            "db" => [
                "default" => [
                    "data_source_name" => "firebase"
                ],
                "production" => [
                    "data_source_name" => "mysql"
                ]
            ]
        ];

        $this->config = new RESTful_Config($data);

        $db = $this->config->get_per_environment('db');

        $this->assertSame(['data_source_name' => 'firebase'], $db);
    }

}*/