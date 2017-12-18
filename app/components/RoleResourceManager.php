<?php
/**
 * Created by PhpStorm.
 * User: Kalu
 * Date: 10/02/2017
 * Time: 16:05
 */
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
class RoleResourceManager
{
    private $acl = null;
    const  ROLE_ADMINISTRATORS = "Administrators";
    const  ROLE_GUESTS = "Guests";
    const RESOURCE_CUSTOMERS = "Customers";
    const RESOURCE_CUSTOMERS_OPERATIONS_SEARCH = "search";
    const RESOURCE_CUSTOMERS_OPERATIONS_CREATE = "create";
    const RESOURCE_CUSTOMERS_OPERATIONS_UPDATE = "update";
//, "create", "update"]


    public function __construct()
    {
        $this->acl = new AclList();
        //Default action is deny access
        //$this->acl->setDefaultAction(Acl::DENY);
        // Create some roles.
// The first parameter is the name, the second parameter is an optional description.
        $roleAdmins = new Role($this::ROLE_ADMINISTRATORS, "Super-User role");
        $roleGuests = new Role($this::ROLE_GUESTS);
        $this->acl->addRole($roleAdmins);
        // Add "Guests" role to ACL
        $this->acl->addRole($roleGuests);

        // Define the "Customers" resource
        $customersResource = new Resource($this::RESOURCE_CUSTOMERS);

        // Add "customers" resource with a couple of operations
        $this->acl->addResource($customersResource, [$this::RESOURCE_CUSTOMERS_OPERATIONS_SEARCH,$this::RESOURCE_CUSTOMERS_OPERATIONS_CREATE,$this::RESOURCE_CUSTOMERS_OPERATIONS_UPDATE]);
        // Set access level for roles into resources

        $this->acl->allow($this::ROLE_GUESTS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_SEARCH);
        $this->acl->allow($this::ROLE_GUESTS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_CREATE);
        $this->acl->deny($this::ROLE_GUESTS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_UPDATE);

        $this->acl->allow($this::ROLE_ADMINISTRATORS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_SEARCH);
        $this->acl->allow($this::ROLE_ADMINISTRATORS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_CREATE);
        $this->acl->allow($this::ROLE_ADMINISTRATORS, $this::RESOURCE_CUSTOMERS, $this::RESOURCE_CUSTOMERS_OPERATIONS_UPDATE);
    }



    public function canUserPerformOperation($userId, $operationId)
    {
        if (empty($userId) || empty($operationId) ) {
            return 0;
        }
        UserRoles::findFirst("");
        return ;
    }
}
