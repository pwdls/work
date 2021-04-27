<?php


class getRequestOne
{
    static private function getServiceQuery($GUID, $type): string
    {
        switch ($type){
            case 'active':
                $result = "SELECT serviceID, price from service WHERE active = 1 and requestGUID = '" . $GUID . "';";
                break;
            case 'required':
                $result = "SELECT serviceID, price from service WHERE required = 1 and requestGUID = '" . $GUID . "';";
                break;
            default:
                $result = "SELECT serviceID, price from service WHERE requestGUID = " . $GUID . ";";
        }
        return $result;
    }

    static private function getService($GUID, $type = 0): array
    {
        $result = array();
        $res = GribovMySQL::getMySQL(getRequestOne::getServiceQuery($GUID, $type));
        foreach ($res as $re) {
            $result[$re['serviceID']] = $re['price'];
        }
        return $result;
    }

    static public function getResult($re): array
    {
        return array(
            'GUID' => $re['GUID'],
            'GUIDPartner' => $re['GUIDPartner'],
            'documentId' => $re['DocumentId'],
            'version' => $re['version'],
            'other_fio' => $re['other_fio'],
            'other_inn' => $re['other_inn'],
            'other_tel' => $re['GUID'],
            'other_email' => $re['other_email'],
            'from_datetime' => $re['from_datetime'],
            'from_location' => $re['from_location'],
            'to_location' => $re['to_location'],
            'containers' => json_decode($re['containers']),
            'other_longdescription' => $re['other_longdescription'],
            'driver_fio' => $re['driver_fio'],
            'passport' => $re['passport'],
            'driver_reg' => $re['driver_reg'],
            'avtodovoz' => $re['avtodovoz'],
            'avtodovoz_location' => $re['avtodovoz_location'],
            'avtodovoz_date' => $re['avtodovoz_date'],
            'avtovivoz' => $re['avtovivoz'],
            'avtovivoz_location' => $re['avtovivoz_location'],
            'avtovivoz_date' => $re['avtovivoz_date'],
            'OrderStatus' => $re['OrderStatus'],
            'servicesRequired' => getRequestOne::getService($re['GUID'], 'required'),
            'servicesAll' => getRequestOne::getService($re['GUID'], 'all'),
            'servicesActive' => getRequestOne::getService($re['GUID'], 'active'),
            'managerComment' => 'коммепнтарий менеджера'
        );
    }
}