<?php
namespace App\language;

class enUS implements Language{
    public function getLanguage(){
        $lang = explode("\\",get_class());
        return end($lang);
    }
}