<?php

namespace PP\Portal\Module;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Address;

/**
 * Description of UserModule.
 *
 * @author user
 */
class AddressModule extends AbstractContainer
{
    public function setValidator($data, Address $address)
    {
        $v = new \Valitron\Validator($data, $address->getFillable());
        $v->rule('required', ['nick_name']);

        return $v;
    }

    public function saveData($data, Address $address)
    {
        $newData = $this->resetData($data);
        $this->save($newData, $address);
    }

    public function updateDate($data, Address $address)
    {
        $this->save($data, $address);
    }

    private function save($data, Address $address)
    {
        foreach ($data as $k => $v) {
            $address->{$k} = $v;
        }
        $address->save();
    }

    private function resetData($data)
    {
        unset($data['id']);
        unset($data['updated_at']);
        unset($data['created_at']);

        return $data;
    }
}
