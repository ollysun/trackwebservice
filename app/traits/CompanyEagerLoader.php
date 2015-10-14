<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
trait CompanyEagerLoader
{
    protected $fetchWith;

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param mixed $fetchWith
     * @return CompanyEagerLoader
     */
    public function setFetchWith($fetchWith)
    {
        $this->fetchWith = $fetchWith;
        return $this;
    }

    /**
     * Joins model with related records
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param \Phalcon\Mvc\Model\Query\BuilderInterface $builder
     * @param $columns
     */
    protected function joinWith(&$builder, &$columns)
    {
        $fetch_with_map =  $this->getFetchWithMap();
        foreach ($fetch_with_map as $fetch_with_field) {
            if (in_array("with_{$fetch_with_field['field']}", $this->fetchWith)) {

                if(isset($fetch_with_field['alias'])) {
                    $builder->innerJoin($fetch_with_field['ref_model_name'], "{$fetch_with_field['alias']}.{$fetch_with_field['reference_key']} = {$fetch_with_field['model_name']}.{$fetch_with_field['foreign_key']}", $fetch_with_field['alias']);
                    $columns[] = "{$fetch_with_field['alias']}.*";
                } else {
                    $builder->innerJoin($fetch_with_field['ref_model_name'], "{$fetch_with_field['ref_model_name']}.{$fetch_with_field['reference_key']} = {$fetch_with_field['model_name']}.{$fetch_with_field['foreign_key']}");
                    $columns[] = "{$fetch_with_field['ref_model_name']}.*";
                }
            }
        }
    }

    /**
     * Returns related records
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $data
     * @return array
     */
    protected function loadRelatedModels($data)
    {
        $result = [];
        $fetch_with_map =  $this->getFetchWithMap();
        foreach ($fetch_with_map as $fetch_with_field) {
            if (in_array("with_{$fetch_with_field['field']}", $this->fetchWith)) {
                if(isset($fetch_with_field['alias'])) {
                    $result[$fetch_with_field['field']] = $data[0]->{$fetch_with_field['alias']}->getData();
                } else {
                    $rowName = lcfirst(\Phalcon\Text::camelize($fetch_with_field['ref_model_name']));
                    $result[$fetch_with_field['field']] = $data[0]->{$rowName}->getData();
                }
            }
        }

        return $result;
    }

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    private function getFetchWithMap()
    {
        return [
            [
                'field' => 'city',
                'model_name' => 'Company',
                'ref_model_name' => 'City',
                'foreign_key' => 'city_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'state',
                'model_name' => 'City',
                'ref_model_name' => 'State',
                'foreign_key' => 'state_id',
                'reference_key' => 'id'
            ],
            [
                'field' => 'primary_contact',
                'model_name' => 'Company',
                'ref_model_name' => 'CompanyUser',
                'foreign_key' => 'primary_contact_id',
                'reference_key' => 'id',
                'alias' => 'PrimaryContact'
            ],
            [
                'field' => 'secondary_contact',
                'model_name' => 'Company',
                'ref_model_name' => 'CompanyUser',
                'foreign_key' => 'secondary_contact_id',
                'reference_key' => 'id',
                'alias' => 'SecondaryContact'
            ],
            [
                'field' => 'relations_officer',
                'model_name' => 'Company',
                'ref_model_name' => 'Admin',
                'foreign_key' => 'relations_officer_id',
                'reference_key' => 'id',
                'alias' => 'RelationsOfficer'
            ]
        ];
    }
}