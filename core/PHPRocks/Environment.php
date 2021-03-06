<?php
namespace PHPRocks;
use PHPRocks\Exception\Environment\CannotGetHost;
use PHPRocks\Util\OptionableArray;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */
final class Environment{

    const UNIT_TEST = 'unit_test';
    const CLI = 'cli';
    const SANDBOX = 'sandbox';
    const DEV = 'development';
    const STAGE = 'stage';
    const PRODUCTION = 'production';

    private static $list = [
        self::SANDBOX,
        self::DEV,
        self::UNIT_TEST,
        self::CLI,
        self::STAGE,
        self::PRODUCTION
    ];

    public static function all(){
        return static::$list;
    }

    public static function isValid($environment){
        return in_array($environment, static::$list);
    }

    /**
     * @return Environment
     */
    public static function factory($unit_test = false){
        $instance = new Environment(
            new OptionableArray($_SERVER),
            $unit_test,
            php_sapi_name()
        );

        return $instance;
    }

    /**
     * @var OptionableArray
     */
    protected $server;

    /**
     * @var bool
     */
    protected $is_unit_test;

    /**
     * @var string
     */
    protected $sapi_name;

    public function __construct(
        OptionableArray $server,
        $is_unit_test,
        $sapi_name
    ){
        $this->server = $server;
        $this->is_unit_test = $is_unit_test;
        $this->sapi_name = $sapi_name;
    }

    public function url(){
        $url = $this->protocol() . '://';
        $url .= $this->domain();
        return $url;
    }

    public function protocol(){

        if( $this->isCommandLine() ){
            return 'cmd';
        }

        $is_ssl = $this->server->get('HTTPS') === 'on' || $this->server->get('SERVER_PORT') == 443;
        $protocol = $is_ssl ? "https" : "http";
        return $protocol;
    }

    public function domain(){
        if( $this->is_unit_test ){
            return self::UNIT_TEST;
        }
        if( $this->sapi_name == 'cli' ){
            $hostname = gethostname();

            if( empty($hostname) ){
                $hostname = $this->server->get('HOSTNAME');
            }

            if( !empty($hostname) ){
                return $hostname;
            }
            return self::CLI;
        }

        $server_name = $this->server->get('HTTP_HOST');

        if( is_null($server_name) ){
            $server_name = $this->server->get('SERVER_NAME');
        }

        if( is_null($server_name) ){
            throw new CannotGetHost($this->server->source());
        }

        return $server_name;
    }

    /**
     * @return bool
     */
    public function isCommandLine(){
        return $this->sapi_name == 'cli' || $this->is_unit_test;
    }

    /**
     * @return boolean
     */
    public function isIsUnitTest() {
        return $this->is_unit_test;
    }

}