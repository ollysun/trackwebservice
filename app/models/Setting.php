<?php

class Setting extends \Phalcon\Mvc\Model
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
  protected $value;

  /**
   *
   * @var string
   */
  protected $last_updated;


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
   * Method to set the value of field value
   *
   * @param string $value
   * @return $this
   */
  public function setValue($value)
  {
    $this->value = json_encode($value);

    return $this;
  }

  /**
   * Method to set the value of field last_updated
   *
   * @param string $last_updated
   * @return $this
   */
  public function setLastUpdated($last_updated)
  {
    $this->last_updated = $last_updated;

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
   * Returns the value of field value
   *
   * @return text
   */
  public function getValue()
  {
    return json_decode($this->value);
  }

  /**
   * Returns the value of field last_updated
   *
   * @return string
   */
  public function getLastUpdated()
  {
    return $this->last_updated;
  }


  /**
   * @return Settings[]
   */
  public static function find($parameters = array())
  {
    return parent::find($parameters);
  }

  /**
   * @return Settings
   */
  public static function findFirst($parameters = array())
  {
    return parent::findFirst($parameters);
  }

  /**
   * Independent Column Mapping.
   */
  public function columnMap()
  {
    return array(
      'id' => 'id',
      'name' => 'name',
      'value' => 'value',
      'last_updated' => 'last_updated'
    );
  }

  public function getData()
  {
    return array(
      'id' => $this->getId(),
      'name' => $this->getName(),
      'value' => $this->getValue(),
      'last_updated' => $this->getLastUpdated(),
    );
  }


  public static function fetchOne($settings_id)
  {
    return Settings::findFirst([
      'id = :id:',
      'bind' => ['id' => $settings_id]
    ]);
  }
}
