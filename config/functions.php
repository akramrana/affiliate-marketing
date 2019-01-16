<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function debugPrint($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function clean($string)
{
    $string = str_replace(' ', '-', trim($string)); // Replaces all spaces with hyphens.
    $string = str_replace('&', 'and', trim($string)); // Replaces all &nbsp with string and

    return preg_replace('/[^A-Za-z0-9\-&]/', '', strtolower($string)); // Removes special chars.
}