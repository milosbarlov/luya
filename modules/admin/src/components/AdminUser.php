<?php

namespace admin\components;

use admin\models\UserOnline;

/**
 * Admin-User component contains informations about the identitiy of the Admin-User
 * 
 * @author nadar
 */
class AdminUser extends \yii\web\User
{
    public $identityClass = '\admin\models\User';

    public $loginUrl = ['/admin/login/index'];

    public $identityCookie = ['name' => '_adminIdentity', 'httpOnly' => true];

    public $enableAutoLogin = false;

    public $idParam = '__luya_adminId';
    
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_LOGOUT, [$this, 'onBeforeLogout']);
    }

    public function onBeforeLogout()
    {
        UserOnline::removeUser($this->getId());
    }
}
