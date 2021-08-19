if(!empty($products)){
    foreach ($arBasket as $key => $value) {
        $mxResult = \CCatalogSKU::getProductList($value);
        if(!empty($mxResult[$value]['ID'])){
            $mainProductId[] = $mxResult[$value]['ID'];
        }else {
            $mainProductId[] = $value;
        }
    }

    if(!empty($mainProductId)){
        $rsElement = CIBlockElement::GetList(
            $arOrder  = array("SORT" => "ASC"),
            $arFilter = array(
                "ID"    => $mainProductId
            ),
            false,
            false,
            $arSelectFields = array("ID", "IBLOCK_ID", "PROPERTY_SOLD", 'ACTIVE')
        );
        while($arElement = $rsElement->fetch()) {
            
            $arProducts[$arElement['ID']] = $arElement;
        } 
       
        foreach ($arBasket as $key => $value){

            $mxResult = \CCatalogSKU::getProductList($value);
            if(!empty($mxResult[$value]['ID'])){
                $mainProdId = $mxResult[$value]['ID'];
            }else{
                $mainProdId = $value;
            }

            if($arProducts[$mainProdId]['PROPERTY_SOLD_VALUE'] == 'Y' || $arProducts[$mainProdId]['ACTIVE'] != 'Y' || empty($arProducts[$mainProdId])){
                \Bitrix\Sale\Internals\BasketTable::delete($key);
            }
        }
    }
}
