<?php

namespace TVHung\Page\Repositories\Interfaces;

use TVHung\Support\Repositories\Interfaces\RepositoryInterface;

interface PageInterface extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getDataSiteMap();

    /**
     * @param array $array
     * @param array $select
     * @return mixed
     */
    public function whereIn(array $array, array $select = []);

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     */
    public function getSearch($query, int $limit = 10);

    /**
     * @param bool $active
     * @return mixed
     */
    public function getAllPages(bool $active = true);
}
