<?php

namespace PP\Portal\dbModel;


class Policies extends \Model
{
    public static $_table = 'policies';

    public static $_id_column = 'Policy_ID';

    public function posts() {
        return $this->belongs_to(__NAMESPACE__ . '\Client','Client_NO');
    }

}
