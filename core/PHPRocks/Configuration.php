<?php
namespace PHPRocks;
use PHPRocks\Exception\Configuration\InvalidPath;
use PHPRocks\Exception\Configuration\ValueNotSet;
use PHPRocks\Exception\Configuration\InvalidEnvironment;
use PHPRocks\Exception\Configuration\EnvironmentNotFound;
use PHPRocks\Util\JSON;
use PHPRocks\Util\OptionableArray;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

final class Configuration{

    /**
     * @var OptionableArray
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

        $data = JSON::decode($file_content, true);
        $this->data = new OptionableArray($data);
    }

    public function environment(){
        $environment = $this->findEnvironment($this->environment->domain());
        return $environment;
    }

    private function findEnvironment($domain){

        try{
            $environment = $this->locateEnvironment($domain);
        }catch (EnvironmentNotFound $exception){
            if( !$this->environment->isCommandLine() ){
                $hostname = gethostname();
                $environment = $this->locateEnvironment($hostname);
            }else{
                throw $exception;
            }
        }

        return $environment;

    }

    private function locateEnvironment($name){

        $environments = $this->get('environments');

        foreach($environments as $environment => $server_names){

            if( in_array($name, $server_names) ){

                if( !Environment::isValid($environment) ){
                    throw new InvalidEnvironment($environment);
                }

                return $environment;
            }

        }

        throw new EnvironmentNotFound($name);

    }

    public function get($key){

        if( !$this->data->has($key) ){
            throw new ValueNotSet($key);
        }

        return $this->data->get($key);

    }

    public function set($key, $value){
        return $this->data->set($key, $value);
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