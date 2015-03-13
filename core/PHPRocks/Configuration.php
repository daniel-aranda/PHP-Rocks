<?php
namespace PHPRocks;
use PHPRocks\Exception\Configuration\InvalidPath;
use PHPRocks\Exception\Configuration\ValueNotSet;
use PHPRocks\Exception\Configuration\InvalidEnvironment;
use PHPRocks\Exception\Configuration\EnvironmentNotFound;
use PHPRocks\Util\JSON;

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
     * @var string
     */
    private $path;

    /**
     * @var \PHPRocks\Environment
     */
    private $environment;

    /**
     * @param null | string $path
     * @return \PHPRocks\Configuration
     */
    public static function instance(
        $path,
        Environment $environment = null
    ){
        $instance = DependencyManager::get(
            '\PHPRocks\Configuration',
            [
                $path,
                $environment
            ]
        );

        return $instance;
    }

    /**
     * @return \PHPRocks\Configuration
     */
    public static function factory(
        $path,
        Environment $environment = null
    ){

        if( is_null($environment) ){
            $environment = Environment::factory();
        }

        $data = new Configuration(
            $path,
            $environment
        );

        return $data;
    }

    public function __construct(
        $path,
        Environment $environment
    ){
        $this->path = $path;
        $this->environment = $environment;

        $this->invalidateData();
    }

    private function invalidateData(){

        if( !is_file($this->path) || !is_readable($this->path) ){
            throw new InvalidPath($this->path);
        }

        $file_content = file_get_contents($this->path);

        $this->data = JSON::decode($file_content, true);
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