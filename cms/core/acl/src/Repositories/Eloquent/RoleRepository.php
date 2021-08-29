<?php

namespace TVHung\ACL\Repositories\Eloquent;

use TVHung\ACL\Repositories\Interfaces\RoleInterface;
use TVHung\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class RoleRepository extends RepositoriesAbstract implements RoleInterface
{
    /**
     * {@inheritDoc}
     */
    public function createSlug($name, $id)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('slug', $slug)->where('id', '!=', $id)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
