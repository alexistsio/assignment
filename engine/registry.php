<?php

class Registry {

    private $vars = array();

    public function Set($key, $val) {
        $this->vars[$key] = $val;
    }

    public function Get($key) {
        return $this->vars[$key];
    }
}