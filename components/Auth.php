<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;

/**
 * Description of Auth
 *
 * @author Akram Hossain <akram.hossain@lezasolutions.com>
 */
class Auth {

    //put your code here
    public $admin_menu = [
        '/dashboard/index'
    ];

    public function checkAccess($role, $menu = '', $controller = null) {
        if ($role == 1) {
            if (in_array($menu, $this->admin_menu)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
