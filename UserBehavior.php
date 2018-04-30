<?php namespace Inetis\BanBackendUsers;

use Backend\Classes\AuthManager;
use Exception;
use System\Classes\ModelBehavior;

class UserBehavior extends ModelBehavior
{

    public function getInetisIsBannedAttribute()
    {
        $userId = $this->model->attributes['id'];
        $throttle = AuthManager::instance()->findThrottleByUserId($userId, null);

        if (!empty($throttle) && $throttle->is_banned) {
            return true;
        }

        return false;
    }


    /**
     * @param $value
     *
     * @throws Exception
     */
    public function setInetisIsBannedAttribute($value)
    {
        $loggedUser = AuthManager::instance()->getUser();
        $userId = $this->model->attributes['id'];

        if ($loggedUser->id == $userId) {
            throw new \ApplicationException("You can't ban yourself");
        }

        $throttle = AuthManager::instance()->findThrottleByUserId($userId, null);

        if ($value) {
            $throttle->ban();

            return;
        }

        $throttle->unban();
    }

}
