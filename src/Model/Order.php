<?php

namespace Endereco\Oxid7Client\Model;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Field;

/**
 * Extends finalizeOrder and writes endereco predictions Fields
 */
class Order extends Order_parent
{
    /**
     * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket
     * @param User $oUser
     * @param $blRecalculatingOrder
     * @return bool|int
     */
    public function finalizeOrder(\OxidEsales\Eshop\Application\Model\Basket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $iRet = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

        if ($this->oxorder__oxdelstreet->value != '') {
            $aAdresses = $oUser->getUserAddresses();
            foreach ($aAdresses as $oAdress) {
                if (
                    $oAdress->oxaddress__oxfname->value == $this->oxorder__oxdelfname->value &&
                    $oAdress->oxaddress__oxlname->value == $this->oxorder__oxdellname->value &&
                    $oAdress->oxaddress__oxstreet->value == $this->oxorder__oxdelstreet->value &&
                    $oAdress->oxaddress__oxstreetnr->value == $this->oxorder__oxdelstreetnr->value &&
                    $oAdress->oxaddress__oxcity->value == $this->oxorder__oxdelcity->value &&
                    $oAdress->oxaddress__oxzip->value == $this->oxorder__oxdelzip->value &&
                    $oAdress->oxaddress__oxcountryid->value == $this->oxorder__oxdelcountryid->value
                ) {
                    $this->oxorder__mojoamsstatus = new Field(
                        $oAdress->oxaddress__mojoamsstatus->rawValue,
                        Field::T_RAW
                    );
                    $this->oxorder__mojoamsts = new Field(
                        $oAdress->oxaddress__mojoamsts->rawValue,
                        Field::T_RAW
                    );
                    $this->oxorder__mojoamspredictions = new Field($oAdress->oxaddress__mojoamspredictions->rawValue, Field::T_RAW);
                    $this->oxorder__mojonamescore = new Field($oAdress->oxaddress__mojonamescore->rawValue, Field::T_RAW);
                    $this->save();
                }
            }
        } elseif ($oUser->oxuser__mojoamsstatus->rawValue) {
            $this->oxorder__mojoamsstatus = new Field($oUser->oxuser__mojoamsstatus->rawValue, Field::T_RAW);
            $this->oxorder__mojoamsts = new Field($oUser->oxuser__mojoamsts->rawValue, Field::T_RAW);
            $this->oxorder__mojoamspredictions = new Field($oUser->oxuser__mojoamspredictions->rawValue, Field::T_RAW);
            $this->oxorder__mojonamescore = new Field($oUser->oxuser__mojonamescore->rawValue, Field::T_RAW);
            $this->save();
        }

        return $iRet;
    }
}
