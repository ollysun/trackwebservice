<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 12/27/2016
 * Time: 4:59 PM
 */
class IntlController extends ControllerBase
{
    public function addZoneAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description');
        //check code duplicate
        if(IntlZone::findFirstByCode($code)){
            return $this->response->sendError('a zone with the same code exists');
        }
        $zone = new IntlZone();
        $zone->setCode($code);
        $zone->setDescription($description);
        if($zone->save()){
            return $this->response->sendSuccess();
        }
        $this->response->sendError(ResponseMessage::INTERNAL_ERROR);
    }

    public function getZonesAction(){

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);
        $with_region = $this->request->getQuery('with_counties');

        $name = $this->request->getQuery('name');

        $filter_by = [];
        $fetch_with = [];

        if($name){
            $filter_by['name'] = $name;
        }

        if($with_region){
            $fetch_with['with_countries'] = 1;
        }

        $bms = IntlZone::fetchAll($offset, $count, $filter_by, $fetch_with, $paginate);
        if(!$paginate) return $this->response->sendSuccess($bms);

        $total_count = IntlZone::getCount($filter_by);
        return $this->response->sendSuccess(['zones' => $bms, 'total_count' => $total_count]);

    }

    public function getCountriesByZoneAction(){
        $zone_id = $this->request->get('zone_id');

        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $countries = IntlZoneCountryMap::getCountries($offset, $count, $zone_id, $paginate);
        if(!$paginate) return $this->response->sendSuccess($countries);

        $total_count = IntlZoneCountryMap::getCount($zone_id);
        return $this->response->sendSuccess(['countries' => $countries, 'total_count' => $total_count]);
    }

    public function mapCountryToZoneAction(){
        $country_id = $this->request->getPost('country_id');
        $zone_id = $this->request->getPost('zone_id');
        if(in_array(null, [$country_id, $zone_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        //validate zone id
        if(!IntlZone::findFirst($zone_id)){
            return $this->response->sendError('Invalid Zone Id');
        }
        //validate country id
        if(!Country::findFirst($country_id)){
            $this->response->sendError('Invalid Country Id');
        }
        //country can be map to only one zone
        $existing_map = IntlZoneCountryMap::findFirstByCountry_id($country_id);
        if($existing_map){
            $existing_map->setZoneId($zone_id);
            if($existing_map->save()){
                return $this->response->sendSuccess();
            }else{
                return $this->response->sendError('Error in updating map');
            }
        }

        $map = new IntlZoneCountryMap();
        $map->setCountryId($country_id);
        $map->setZoneId($zone_id);
        if($map->save()) return $this->response->sendSuccess();
        return $this->response->sendError(ResponseMessage::INTERNAL_ERROR);
    }

    public function addWeightRangeAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $min_weight = $this->request->getPost('min_weight');
        $max_weight = $this->request->getPost('max_weight');

        if(in_array(null, [$min_weight, $max_weight])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        //add validations

        $range = new IntlWeightRange();
        $range->setMinWeight($min_weight);
        $range->setMaxWeight($max_weight);
        if($range->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::INTERNAL_ERROR);
    }

    public function editWeightRange(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('id');
        $min_weight = $this->request->getPost('min_weight');
        $max_weight = $this->request->getPost('max_weight');

        if(in_array(null, [$id, $min_weight, $max_weight])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        //add validations

        $range = IntlWeightRange::findFirst(['id = :id"', 'bind' => ['id' => $id]]);
        if(!$range) return $this->response->sendError('Weight range not found');
        $range->setMinWeight($min_weight);
        $range->setMaxWeight($max_weight);
        if($range->save()){
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::INTERNAL_ERROR);
    }

    public function getWeightRangesAction(){
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);

        $weight_ranges = IntlWeightRange::fetchAll($offset, $count, $paginate);
        if(!$paginate) return $this->response->sendSuccess($weight_ranges);

        $total_count = IntlWeightRange::getCount();
        return $this->response->sendSuccess(['weight_ranges' => $weight_ranges, 'total_count' => $total_count]);
    }

    public function saveTariffAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('id');
        $zone_id = $this->request->getPost('zone_id');
        $weight_range_id = $this->request->getPost('weight_range_id');
        $base_amount = $this->request->getPost('base_amount');
        $parcel_type_id = $this->request->getPost('parcel_type_id');
        $increment = $this->request->getPost('increment');
        if(in_array(null, [$zone_id, $weight_range_id, $parcel_type_id, $base_amount])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        //validate zone id
        if(!IntlZone::findFirst($zone_id)){
            return $this->response->sendError('Invalid zone id');
        }
        //validate weight range
        if(!IntlWeightRange::findFirst($weight_range_id)){
            return $this->response->sendError('Invalid weight range');
        }
        //validate parcel type
        if(!ShippingType::findFirst(['id = :id:', 'bind' => ['id' => $parcel_type_id]])){
            return $this->response->sendError('Invalid parcel type');
        }
        if(!$id){
            if(IntlTariff::findFirst([
                'conditions' => 'zone_id = :zone_id: AND weight_range_id = :weight_range_id: AND parcel_type_id = :parcel_type_id:',
                'bind' => ['zone_id' => $zone_id, 'weight_range_id' => $weight_range_id, 'parcel_type_id' => $parcel_type_id ]
            ])){
                return $this->response->sendError('Duplicate error');
            }
            $tariff = new IntlTariff();
            $tariff->setBaseAmount($base_amount);
            $tariff->setIncrement($increment);
            $tariff->setParcelTypeId($parcel_type_id);
            $tariff->setWeightRangeId($weight_range_id);
            $tariff->setZoneId($zone_id);
            if($tariff->save()) return $this->response->sendSuccess();
            return $this->response->sendError();
        }
        $tariff = IntlTariff::findFirst($id);
        if(!$tariff) return $this->response->sendError('Price Not Found');

        $tariff->setBaseAmount($base_amount);
        $tariff->setIncrement($increment);
        if($tariff->save()) return $this->response->sendSuccess();
        return $this->response->sendError();
    }

    public function getTariffsAction(){
        $offset = $this->request->getQuery('offset', null, DEFAULT_OFFSET);
        $count = $this->request->getQuery('count', null, DEFAULT_COUNT);
        $paginate = $this->request->getQuery('paginate', null, false);
        $with_weight_range = $this->request->getQuery('with_weight_range', null, false);
        $with_zone  = $this->request->getQuery('with_zone',  null, false);
        $with_parcel_type = $this->request->getQuery('with_parcel_type',  null, false);

        $zone_id = $this->request->getQuery('zone_id');
        $tariff_id = $this->request->getQuery('id');

        $filter_by = [];
        $fetch_with = [];

        if($tariff_id){
            $filter_by['tariff_id'] = $tariff_id;
        }

        if($zone_id){
            $filter_by['zone_id'] = $zone_id;
        }

        if($with_parcel_type){
            $fetch_with['with_parcel_type'] = true;
        }
        if($with_zone){
            $fetch_with['with_zone'] = true;
        }
        if($with_weight_range){
            $fetch_with['with_weight_range'] = true;
        }


        $bms = IntlTariff::fetchAll($offset, $count, $filter_by, $fetch_with, $paginate);
        if($tariff_id && count($bms) > 0) {
            $bms = $bms[0];
        }
        if(!$paginate) return $this->response->sendSuccess($bms);

        $total_count = IntlTariff::getCount($filter_by);
        return $this->response->sendSuccess(['tariffs' => $bms, 'total_count' => $total_count]);

    }

    public function deleteTariffAction(){
        $id = $this->request->getPost('id');
        if(!$id) return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        $tariff = IntlTariff::findFirst(['id = :id:', 'bind' => ['id' => $id]]);
        if(!$tariff) return $this->response->sendError('Tariff Not Found');
        $tariff->delete();
        return $this->response->sendSuccess();
    }


    public function calculateAmountAction(){
        $weight = $this->request->getPost('weight');
        $country_id = $this->request->getPost('country_id');
        $parcel_type_id = $this->request->getPost('parcel_type_id');

        if(in_array(null, [$weight, $country_id, $parcel_type_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        $result = IntlZone::calculateBilling($weight, $country_id, $parcel_type_id);
        if($result['success']){
            return $this->response->sendSuccess($result['amount']);
        }
        return $this->response->sendError($result['message']);

        /** @var Country $country */
        /*$country = Country::findFirstById($country_id);
        if(!$country){
            return $this->response->sendError('Invalid country id');
        }*/

        /** @var IntlZoneCountryMap $zone */
        /*$zone_map = IntlZoneCountryMap::findFirst(['conditions' => 'country_id = :country_id:', 'bind' => ['country_id' => $country_id]]);
        if(!$zone_map){
            return $this->response->sendError('Country not mapped');
        }*/

        /** @var ParcelType $parcel_type */
        /*$parcel_type = ParcelType::findFirstById($parcel_type_id);
        if(!$parcel_type){
            return $this->response->sendError('Invalid parcel type');
        }*/


        /*$weight_range = IntlWeightRange::findFirst(['conditions' =>
            ':weight: between min_weight AND max_weight', 'bind' => ['weight' => $weight]]);
        if(!$weight_range){
            return $this->response->sendError('Weight not in range');
        }

        $tariff = IntlTariff::findFirst(['conditions' => 'zone_id = :zone_id: AND parcel_type_id = :parcel_type_id:
        AND weight_range_id = :weight_range_id:',
            'bind' => ['zone_id' => $zone_map->getZoneId(), 'parcel_type_id' => $parcel_type_id,
                'weight_range_id' => $weight_range->getId()]]);
        if(!$tariff){
            return $this->response->sendError('Tariff not found');
        }
        return $this->response->sendSuccess($tariff->getBaseAmount());*/

    }
}