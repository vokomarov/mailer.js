<?php

class Template{

    protected $tpl_dir = "/template/";
    protected $tpl_content = "";

    public function __construct($data = array()){

        if(isset($data['tpl_dir']))
            $this->tpl_dir = $data['tpl_dir'];

        if(isset($data['name']) && $data['name'] !== "")
            $this->setTemplate($data['name']);

    }


    public function setTemplate($tpl_name){
        $path = $this->tpl_dir.$tpl_name;
        if(file_exists($path)){
            ob_start();
            include($path);
            $this->tpl_content = ob_get_clean();
            return true;
        }else{
            return false;
        }
    }

    //
    function replace($data){
        $this->tpl_content = str_replace(array_keys($data), array_values($data), $this->tpl_content);
        return true;
    }

    //set variable
    function set($key, $value){
        $replace['{'.$key.'}'] = $value;
        $this->replace($replace);
        return true;
    }

    //get compiled
    function get(){
        return $this->tpl_content;
    }
}
