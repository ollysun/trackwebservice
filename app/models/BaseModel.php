<?php
use Phalcon\Mvc\Model;

/**
 * Class BaseModel
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class BaseModel extends Model
{

    /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnCreate()
    {
        $this->created_at = Util::getCurrentDateTime();
        $this->updated_at = Util::getCurrentDateTime();
    }

    /**
     * Set created_at before validation
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function beforeValidationOnUpdate()
    {
        $this->updated_at = Util::getCurrentDateTime();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $called_class = get_called_class();
        $model = new $called_class();
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }
        $status = $model->save();
        return $status;
    }

}
