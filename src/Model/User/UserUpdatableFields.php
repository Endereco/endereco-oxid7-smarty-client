<?php

namespace Endereco\Oxid7Client\Model\User;

class UserUpdatableFields extends UserUpdatableFields_parent
{

    public function getUpdatableFields()
    {
        $aReturn = parent::getUpdatableFields();
        $aReturn[] = 'MOJOAMSTS';
        $aReturn[] = 'MOJOAMSSTATUS';
        $aReturn[] = 'MOJOAMSPREDICTIONS';
        $aReturn[] = 'MOJONAMESCORE';
        $aReturn[] = 'MOJOADDRESSHASH';

        return $aReturn;
    }
}
