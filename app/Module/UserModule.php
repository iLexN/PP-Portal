<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PP\Module;

use PP\Portal\dbModel\User;

/**
 * Description of UserModule
 *
 * @author user
 */
class UserModule {

    /**
     * @var \Slim\Container
     */
    protected $c;

    /**
     * @var User
     */
    public $user;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    public function create($data){
        /* @var $user User */
        $user = User::create();
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->genToken();
        $user->active = 0;
        $user->save();
        return $user;
    }

    public function isUserExist($data) {
        $user = User::where('email',$data['email'])->findOne();
        if ( $user && password_verify($data['password'],$user->password) ){
            $this->user = $user;
            return true;
        }
        return false;
    }
}
