<?php

class template {
    private $var_list;
    public function assign($name, $value) {
        $this->var_list[$name] = $value;
    }

    //~ public function display() {
        //~ extra($this->var_list);
    //~ }

    public function fetch($t_name) {
        extract($this->var_list);
        ob_start();
        include 'tpl/' . $t_name . '.php';
        $str = ob_get_clean();
        return $str;
    }
}
