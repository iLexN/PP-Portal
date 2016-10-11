<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;

/**
 * Description of UserModule.
 *
 * @author user
 */
class UserPreferenceModule extends AbstractContainer
{
    /**
     * @return \PP\Portal\DbModel\UserPreference
     */
    public function newPreference()
    {
        $info = $this->UserModule->user->userPreference()->create([
                'currency'         => 'USD',
                'currency_receive' => 'USD',
            ]);
        //$this->clearCache();

        return $info;
    }

    /**
     * @return \PP\Portal\DbModel\UserPreference
     */
    public function getByUserID()
    {
        //$id = $this->UserModule->user->ppmid;

        //$item = $this->pool->getItem('User/'.$id.'/Preference');

        //$info = $item->get();

        //if ($item->isMiss()) {
        //    $item->lock();
        //    $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $info = $this->UserModule->user->userPreference()->first();
        //    $this->pool->save($item->set($info));
        //}

        return $info;
    }

    //public function clearCache()
    //{
    //    $id = $this->UserModule->user->ppmid;
    //    $this->pool->deleteItem('User/'.$id.'/Preference');
    //}
}
