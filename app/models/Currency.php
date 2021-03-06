<?php
/**
 * Currency
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2017-01-02, 08:10:42
 */
class Currency extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @var string
     */
    protected $decimal_unicode;

    /**
     *
     * @var string
     */
    protected $hexadecimal_unicode;

    /**
     *
     * @var integer
     */
    protected $country_id;

    /**
     *
     * @var double
     */
    protected $conversion_rate;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Method to set the value of field decimal_unicode
     *
     * @param string $decimal_unicode
     * @return $this
     */
    public function setDecimalUnicode($decimal_unicode)
    {
        $this->decimal_unicode = $decimal_unicode;

        return $this;
    }

    /**
     * Method to set the value of field hexadecimal_unicode
     *
     * @param string $hexadecimal_unicode
     * @return $this
     */
    public function setHexadecimalUnicode($hexadecimal_unicode)
    {
        $this->hexadecimal_unicode = $hexadecimal_unicode;

        return $this;
    }

    /**
     * Method to set the value of field country_id
     *
     * @param integer $country_id
     * @return $this
     */
    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;

        return $this;
    }

    /**
     * Method to set the value of field conversion_rate
     *
     * @param double $conversion_rate
     * @return $this
     */
    public function setConversionRate($conversion_rate)
    {
        $this->conversion_rate = $conversion_rate;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field decimal_unicode
     *
     * @return string
     */
    public function getDecimalUnicode()
    {
        return $this->decimal_unicode;
    }

    /**
     * Returns the value of field hexadecimal_unicode
     *
     * @return string
     */
    public function getHexadecimalUnicode()
    {
        return $this->hexadecimal_unicode;
    }

    /**
     * Returns the value of field country_id
     *
     * @return integer
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Returns the value of field conversion_rate
     *
     * @return double
     */
    public function getConversionRate()
    {
        return $this->conversion_rate;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('country_id', 'Country', 'id', array('alias' => 'Country'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'currencies';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Currency[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Currency
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'name' => 'name',
            'code' => 'code',
            'decimal_unicode' => 'decimal_unicode',
            'hexadecimal_unicode' => 'hexadecimal_unicode',
            'country_id' => 'country_id',
            'conversion_rate' => 'conversion_rate'
        );
    }


    public function getData(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'decimal_unicode' => $this->getDecimalUnicode(),
            'hexadecimal_unicode' => $this->getHexadecimalUnicode(),
            'country_id' => $this->getCountryId(),
            'conversion_rate' => $this->getConversionRate()
        );
    }

    public function init($data){
        $this->setCountryId($data['country_id']);
        $this->setName($data['name']);
        $this->setCode($data['code']);
        $this->setDecimalUnicode($data['decimal_unicode']);
        $this->setHexadecimalUnicode($data['hexadecimal_unicode']);
        $this->setConversionRate($data['conversion_rate']);
    }

    /**
     * @param $offset
     * @param $count
     * @param $filter_by
     * @param $fetch_with
     * @param bool|false $paginate
     * @return array
     */
    public static function fetchAll($offset, $count, $filter_by, $fetch_with, $paginate = false){
        $builder = new \Phalcon\Mvc\Model\Query\Builder();
        $builder->addFrom('Currency', 'Currency');
        $columns = ['Currency.*'];
        $bind = [];
        if ($paginate) {
            $builder = $builder->limit($count, $offset);
        }

        if($fetch_with){
            if($fetch_with['with_country']){
                $builder->leftJoin('Country', 'Country.id = Currency.country_id', 'Country');
                $columns[] = 'Country.*';
            }
        }

        $builder->columns($columns);

        if(isset($filter_by['country_id'])){
            $builder->where("Currency.country_id = :currency_id:");
            $bind['currency_id'] = $filter_by['currency_id'];
        }

        $data = $builder->getQuery()->execute($bind);

        $currencies = [];
        foreach ($data as $item) {
            if($fetch_with['with_country']){
                $currency = $item->currency->toArray();
                $currency['country'] = $item->country->toArray();
            }else{
                $currency = $item->toArray();
            }
            $currencies[] = $currency;
        }
        return $currencies;
    }

    public static function getCount($filter_by = array()){
        $obj = new BusinessManager();
        $builder = $obj->getModelsManager()->createBuilder()
            ->columns('COUNT(*) AS count')
            ->from('Currency');

        //filters
        $where = [];
        $bind = [];

        if (!empty($filter_by['country_id'])) {
            $where[] = 'Currency.country_id = :country_id:';
            $bind['country_id'] = $filter_by['country_id'];
        }


        $builder->where(join(' AND ', $where));
        $data = $builder->getQuery()->execute($bind);

        if (count($data) == 0) {
            return null;
        }

        return intval($data[0]->count);
    }

}
