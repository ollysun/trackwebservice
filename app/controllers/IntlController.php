<?php

/**
 * Created by PhpStorm.
 * User: ELACHI
 * Date: 12/27/2016
 * Time: 4:59 PM
 */
class IntlController extends ControllerBase
{

  /**
   * Update a Zone
   *
   * @return object
   */
  public function updateZoneAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('zone');
        $code = $this->request->getPost('code');
        $description = $this->request->getPost('description');
        $extra = $this->request->getPost('percent');
        $sign = $this->request->getPost('sign');

        if(!$zone = IntlZone::findFirstById(!empty($id) ? $id : '')){
          return $this->response->sendError('Zone does not exist');
        }

        if($zone->save(
          [
            'code' => $code,
            'description' => $description,
            'sign' => $sign,
            'extra_percent_on_import' => $extra])){
          return $this->response->sendSuccess('Zone saved successfully');
        }

        $this->response->sendError(ResponseMessage::INTERNAL_ERROR);
    }

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

        $id = $this->request->getQuery('id');

        $filter_by = [];
        $fetch_with = [];

        if($id){
            $filter_by['id'] = $id;
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

    public function editWeightRangeAction(){
        $this->auth->allowOnly(Role::ADMIN);
        $id = $this->request->getPost('id');
        $min_weight = $this->request->getPost('min_weight');
        $max_weight = $this->request->getPost('max_weight');

        if(in_array(null, [$id, $min_weight, $max_weight])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }
        //add validations

        //$range = IntlWeightRange::findFirst(['id = :id"', 'bind' => ['id' => $id]]);
        $range = IntlWeightRange::findFirst($id);
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

    public function getWeightRangeAction(){
        $min = $this->request->getQuery('min');
        $weight_range = IntlWeightRange::findFirst(['min_weight = :min:', 'bind' => ['min' => $min]]);
        if(!$weight_range) return $this->response->sendError('Record Not Found');
        return $this->response->sendSuccess($weight_range);
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

    public function deleteAction()
    {
        $this->auth->allowOnly([Role::ADMIN, Role::OFFICER]);
        $weight_range_id = $this->request->getPost('weight_range_id');
        $weight_range_ids = $this->request->getPost('weight_range_ids');
        $force_delete = $this->request->getPost('force_delete');

        $response = $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        if($weight_range_id){
            $response = $this->delete($weight_range_id, $force_delete);
        }elseif($weight_range_ids){
            $weight_range_ids = explode(',', $weight_range_ids);
            foreach ($weight_range_ids as $weight_range_id) {
                $response = $this->delete($weight_range_id, $force_delete);
            }
        }
        return $response;
    }

    private function delete($weight_range_id, $force_delete){
        // Check if weight range is valid
        $weightRange = IntlWeightRange::findFirst($weight_range_id);


        if(!$weightRange) {
            return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_DOES_NOT_EXIST);
        }
        //$fetch_with = ['with_zone' => true];
        $fetch_with = [];

        $filter_by = [];
        $tariff = IntlTariff::find(['weight_range_id = :id:', 'bind' => ['id' => $weight_range_id]]);
        $weightBillings = $tariff->toArray();

        // Check if there's a pricing for the weight range
        //$weightBillings = IntlTariff::fetchAll(DEFAULT_OFFSET, DEFAULT_COUNT,$filter_by, $fetch_with);
        //dd(empty($weightBillings));
        if(!empty($weightBillings)) {
            if($force_delete == '1'){//if forcing the delete then delete the prices
                foreach ($weightBillings as $weightBilling) {
                    $billing = IntlTariff::findFirst(['id = :id:', 'bind' => ['id' => $weightBilling['id']]]);
                    if ($billing != false) {
                        $billing->delete();
                    }
                }
            }else return $this->response->sendError(ResponseMessage::WEIGHT_RANGE_STILL_HAS_EXISTING_BILLING);
        }

        if($weightRange->delete()) {
            return $this->response->sendSuccess();
        }
        return $this->response->sendError(ResponseMessage::UNABLE_TO_DELETE_WEIGHT_RANGE);
    }


    public function calculateAmountAction(){
        $weight = $this->request->getPost('weight');
        $country_id = $this->request->getPost('country_id');
        $parcel_type_id = $this->request->getPost('parcel_type_id');

        if(in_array(null, [$weight, $country_id, $parcel_type_id])){
            return $this->response->sendError(ResponseMessage::ERROR_REQUIRED_FIELDS);
        }

        //dd([$weight, $country_id, $parcel_type_id]);

        $result = IntlZone::calculateBilling($weight, $country_id, $parcel_type_id);
        if($result['success']){
            return $this->response->sendSuccess($result['amount']);
        }
        return $this->response->sendError($result['message']);


    }
}