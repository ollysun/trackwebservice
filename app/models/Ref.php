<?php


/**
 * Class Ref
 * =========
 * Provides methods that are common between all ref tables.
 */
class Ref  extends \Phalcon\Mvc\Model {

    public static function fetch($model, $conditions=null, $binds=array()){
        $ref = new Ref();
        $builder = $ref->getModelsManager()->createBuilder()
            ->from($model)
            ->orderBy('name');

        if ($conditions != null){
            $builder->where($conditions);
        }

        $data = $builder->getQuery()->execute($binds);

        return $data->toArray();
    }
} 