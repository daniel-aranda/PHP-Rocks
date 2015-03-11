<?php
namespace PHPRocks;
use PHPRocks\Exception\Configuration\ValueNotSet;
use PHPRocks\Exception\Configuration\InvalidEnvironment;
use PHPRocks\Exception\Configuration\EnvironmentNotFound;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

final class Configuration{

    /**
     * @var array
     */
    private $data;

    /**
     * @var \PHPRocks\Environment
     */
    private $environment;

    /**
     * @param \PHPRocks\Configuration $path
     */
    public static function instance($path = null){
        $instance = DependencyManager::get(
            '\PHPRocks\Configuration',
            [$path]
        );

        return $instance;
    }

    /**
     * @return \PHPRocks\Configuration
     */
    public static function factory(
        $path = null,
        Environment $environment = null
    ){

        if( is_null($path) ){
            //TODO: make this required, so throw Exception if not set
            $path = 'PHPRocks-configuration.json';
        }

        if( is_null($environment) ){
            $environment = Environment::factory();
        }

        $file_content = file_get_contents($path);

        $data = new Configuration(
            json_decode($file_content, true),
            $environment
        );

        return $data;
    }

    public function __construct(
        array $data,
        Environment $environment
    ){
        $this->data = $data;
        $this->environment = $environment;
    }

    public function environment(){
        $environment = $this->findEnvironment($this->environment->domain());
        return $environment;
    }

    private function findEnvironment($domain){

        $environments = $this->get('environments');

        foreach($environments as $environment => $server_names){

            if( in_array($domain, $server_names) ){

                if( !Environment::isValid($environment) ){
                    throw new InvalidEnvironment($environment);
                }

                return $environment;
            }

        }

        throw new EnvironmentNotFound($domain);

    }

    public function get($key){

        $keys = explode('.', $key);
        $child = $this->data;

        foreach($keys as $current_key){
            if( !isset($child[$current_key]) ){
                throw new ValueNotSet($key);
            }
            $child = $child[$current_key];
        }

        return $child;

    }

    public function set($key, $value){
        $keys = explode('.', $key);
        $child = &$this->data;

        foreach($keys as $index => $current_key){
            if( $index === count($keys) - 1 ){
                $child[$current_key] = $value;
            }else{
                if( !isset($child[$current_key]) ){
                    $child[$current_key] = [];
                }
                $child = &$child[$current_key];
            }
        }

        return true;
    }

    public function getPerEnvironment($key, $environment = null){

        if( is_null($environment) ){
            $environment = $this->environment();
        }

        if( !Environment::isValid($environment) ){
            throw new InvalidEnvironment($environment);
        }

        try{
            $value = $this->get($key . '.' . $environment);
        }catch(ValueNotSet $e){
            $value = $this->get($key . '.default');
        }

        return $value;
    }

}