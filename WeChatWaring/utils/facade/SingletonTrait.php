<?php

namespace apps\spcenter\utils;

trait MFacade_SingletonTrait {

    private static $instance = null;

    /**
     * @return self
     */
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}