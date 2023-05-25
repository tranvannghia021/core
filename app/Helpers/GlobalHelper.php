<?php

if(!function_exists("coreArray")){
    function coreArray($data){
        if(is_null($data)) return [];
        if(is_array($data)) return $data;
        return $data->toArray();
    }
}
