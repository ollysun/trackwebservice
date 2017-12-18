<?php

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
interface EagerLoaderInterface
{
    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param mixed $fetchWith
     * @return CompanyEagerLoader
     */
    public function setFetchWith($fetchWith);

    /**
     * Joins model with related records
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param \Phalcon\Mvc\Model\Query\BuilderInterface $builder
     * @param $columns
     */
    public function joinWith(&$builder, &$columns);

    /**
     * Returns related records
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $data
     * @return array
     */
    public function loadRelatedModels($data);

    /**
     * Returns an array that maps related models
     * @author Adegoke Obasa <goke@cottacush.com>
     * @return array
     */
    public function getFetchWithMap();
}