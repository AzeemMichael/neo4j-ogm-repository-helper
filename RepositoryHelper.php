<?php

namespace AppBundle\Utils;

/**
 * @author Azeem Michael
 */
trait RepositoryHelper {

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private function createBaseQuery(array $params) {
        try {
            $tokens = explode('\\', get_class($this));
            $label  = str_replace('Repository', '', end($tokens));

            return $this->getEntityManager()
                        ->createCypherQuery()
                        ->query('MATCH ('.$label.'s:'.$label.' {'.implode(',',array_map(function($k,$v) {
                            return "$k: \"$v\"";
                        }, array_keys($params), $params)).'}) RETURN '.$label.'s');
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public final function findOneBy(array $params) {
        try {
            return $this->createBaseQuery($params)
                        ->limit(1)
                        ->getOne();
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public final function findBy(array $params) {
        try {
            return $this->createBaseQuery($params)
                        ->getList();
        } catch(\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public final function findAll() {
        try {
            $tokens = explode('\\', get_class($this));
            $label  = str_replace('Repository', '', end($tokens));

            return $this->getEntityManager()
                        ->createCypherQuery()
                        ->query('MATCH ('.$label.'s:'.$label.') RETURN '.$label.'s')
                        ->getList();
        } catch(\Exception $e) {
            throw $e;
        }
    }
}
