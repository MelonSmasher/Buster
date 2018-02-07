<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 1/31/18
 * Time: 4:01 PM
 */

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * @param User $user
     */
    public function created(User $user)
    {
        $user->createApiKey();
    }

    /**
     * @param User $user
     */
    public function restored(User $user)
    {
        $user->createApiKey();
    }

    /**
     * @param User $user
     */
    public function deleting(User $user)
    {
        foreach ($user->apiKeys() as $apiKey) {
            $apiKey->delete();
        }
    }

}