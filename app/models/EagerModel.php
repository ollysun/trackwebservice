<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
abstract class EagerModel extends BaseModel implements EagerLoaderInterface
{
    protected $fetchWith;

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param mixed $fetchWith
     * @return EagerLoaderInterface
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
    public function joinWith(&$builder, &$columns)
    {
        $fetch_with_map =  $this->getFetchWithMap();
        foreach ($fetch_with_map as $fetch_with_field) {
            // Default to inner join, if no join type is set
            if(!isset($fetch_with_field['join_type'])) {
                $fetch_with_field['join_type'] = 'inner';
            }

            // Default to inner join, if invalid join type is set
            if(!in_array($fetch_with_field['join_type'], ['left', 'inner', 'right'])) {
                $fetch_with_field['join_type'] = 'inner';
            }

            if (in_array("with_{$fetch_with_field['field']}", $this->fetchWith)) {
                if(isset($fetch_with_field['alias'])) {
                    call_user_func_array([$builder, "{$fetch_with_field['join_type']}Join"], [
                        $fetch_with_field['ref_model_name'],
                        "{$fetch_with_field['alias']}.{$fetch_with_field['reference_key']} = {$fetch_with_field['model_name']}.{$fetch_with_field['foreign_key']}",
                        $fetch_with_field['alias']
                    ]);
                    $columns[] = "{$fetch_with_field['alias']}.*";
                } else {
                    call_user_func_array([$builder, "{$fetch_with_field['join_type']}Join"], [
                        $fetch_with_field['ref_model_name'],
                        "{$fetch_with_field['ref_model_name']}.{$fetch_with_field['reference_key']} = {$fetch_with_field['model_name']}.{$fetch_with_field['foreign_key']}"
                    ]);
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
    public function loadRelatedModels($data, $all = false)
    {
        $result = [];
        $fetch_with_map =  $this->getFetchWithMap();
        $data = $all !== false ? $data : $data[0];
        foreach ($fetch_with_map as $fetch_with_field) {
            if (in_array("with_{$fetch_with_field['field']}", $this->fetchWith)) {
                if(isset($fetch_with_field['alias'])) {
                    $result[$fetch_with_field['field']] = $data->{$fetch_with_field['alias']}->toArray();
                } else {
                    $rowName = lcfirst($fetch_with_field['ref_model_name']);
                    $result[$fetch_with_field['field']] = $data->{$rowName}->toArray();
                }
            }
        }

        return $result;
    }
}