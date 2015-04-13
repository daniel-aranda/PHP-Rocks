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

    public function get($key){

        $keys = explode('.', $key);
        $child = $this->list;

        foreach($keys as $current_key){
            if( !isset($child[$current_key]) ){
                return null;
            }
            $child = $child[$current_key];
        }

        return $child;

    }

    public function set($key, $value){
        $keys = explode('.', $key);
        $child = &$this->list;

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

    public function has($key){
        $keys = explode('.', $key);
        $child = $this->list;

        foreach($keys as $current_key){
            if( !array_key_exists($current_key, $child) ){
                return false;
            }
            $child = $child[$current_key];
        }

        return true;
    }

    public function source(){
        return $this->list;
    }

}