<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class Route extends \Phalcon\Mvc\Model
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
    protected $created_date;
    /**
     *
     * @var string
     */
    protected $updated_date;
    /**
     *
     * @var integer
     */
    protected $branch_id;
    /**
     *
     * @var integer
     */
    protected $status;

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     */
    public function initialize()
    {
        $this->setSource('routes');
        $this->hasOne('branch_id', 'Branch', 'id', array('alias' => 'Branch'));
    }

    /**
     * Creates Route
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $routeName
     * @param $branchId
     * @return bool|Route
     */
    public static function createRoute($routeName, $branchId)
    {
        $route = new Route();
        $route->name = $routeName;
        $route->branch_id = $branchId;
        $route->code = new Phalcon\Db\RawValue(null);
        $route->status = Status::ACTIVE;
        $route->created_date = Util::getCurrentDateTime();
        $route->updated_date = Util::getCurrentDateTime();

        if (!$route->save()) {
            return false;
        }

        $route->refresh(); // Ensure DB version is loaded
        return $route->toArray();
    }

    /**
     * Gets all routes
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public static function getAll($branchId)
    {
        $query = Route::query()
            ->columns("Route.id, Route.name, Route.code, Route.created_date, Route.updated_date, Branch.name AS branch_name, Branch.code AS branch_code,  Route.branch_id")
            ->innerJoin('Branch');

        if (!is_null($branchId)) {
            $query->where('branch_id = :branch_id:', ['branch_id' => $branchId]);
        }
        $query = $query->execute();

        return $query->toArray();
    }

    /**
     * Returns the details of the route
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array
     */
    public function getData() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'created_date' => $this->created_date,
            'branch_id' => $this->branch_id
        );
    }

    /**
     * Updates the details of a route
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array
     */
    public function editDetails($name, $branch_id)
    {
        $this->name = $name;
        $this->branch_id = $branch_id;
        $this->updated_date = Util::getCurrentDateTime();
    }
}