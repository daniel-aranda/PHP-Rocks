<?php
namespace PHPRocks\Util;

/**
 * PHP Rocks :: Fat Free Framework
 * @author: Daniel Aranda (https://github.com/daniel-aranda/)
 *
 */

class OptionableArray
{

    /**
     * @var array
     */
    protected  $list;

    public function __construct(array $list){
        $this->list = $list;
    }

    public function set($key, $value){
        $this->list[$key] = $value;

        return $value;
    }

    public function get($key){
        if( !$this->has($key) ){
            return null;
        }
        $value = $this->list[$key];

        return $value;
    }

    public function has($key){
        return array_key_exists($key, $this->list);
    }

    public function source(){
        return $this->list;
    }

}