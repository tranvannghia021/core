<?php

if (!function_exists("coreArray")) {
    function coreArray($data)
    {
        if (is_null($data)) {
            return [];
        }
        if (is_array($data)) {
            return $data;
        }
        return $data->toArray();
    }
}
if (!function_exists('getIdUser')) {
    function getIdUser()
    {
        return @request()->input('userInfo.id') ?? @request()->header('userId');
    }
}
