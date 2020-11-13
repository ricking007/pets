<?php
namespace App\language;

class esES implements Language{
    public function getLanguage(){
        $lang = explode("\\",get_class());
        return end($lang);
    }
  
}