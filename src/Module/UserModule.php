<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Client;

/**
 * Description of UserModule.
 *
 * @author user
 */
class UserModule extends AbstractContainer
{
    /**
     * @var Client
     */
    public $client;

    protected $fields = [
        'Client_NO',
        'Title',
        'First_Name',
        'Middle_Name',
        'Surname',
        'Company_Name',
        'Mobile_One',
        'Mobile_Two',
        'Home_Phone',
        'Business_Phone',
        'Home_Fax',
        'Business_Fax',
        'Email',
        'Second_Email',
        'Person_One_Skype',
        'Home_Address_1',
        'Home_Address_2',
        'Home_Address_3',
        'Home_Address_4',
        'Home_Address_5',
    ];

    public function isUserExistByID($id)
    {
        $item = $this->c['pool']->getItem('Client/'.$id.'/info');

        /* @var $user Client */
        $client = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $client = Client::select($this->fields)->find($id);
            $this->c['pool']->save($item->set($client));
        }

        if ($client) {
            $this->client = $client;

            return true;
        }

        return false;
    }
}
