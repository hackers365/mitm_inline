<?php

class RedisExt extends Redis{
    private $is_connect = false;
    public function __construct() {
        $this->connect('127.0.0.1', 6379);
    }
}
