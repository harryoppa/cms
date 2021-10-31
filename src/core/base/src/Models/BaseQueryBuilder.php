<?php

namespace TVHung\Base\Models;

use Illuminate\Database\Eloquent\Builder;

class BaseQueryBuilder extends Builder
{
    /**
     * @param string $column
     * @param string|null $term
     * @return BaseQueryBuilder
     */
    public function addSearch(string $column, ?string $term)
    {
        $searchTerms = explode(' ', $term);

        $sql = 'LOWER(' . $this->getGrammar()->wrap($column) . ') LIKE ? ESCAPE ?';

        foreach ($searchTerms as $searchTerm) {
            $searchTerm = mb_strtolower($searchTerm, 'UTF8');
            $searchTerm = str_replace('\\', $this->getBackslashByPdo(), $searchTerm);
            $searchTerm = addcslashes($searchTerm, '%_');

            $this->orWhereRaw($sql, ['%' . $searchTerm . '%', '\\']);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getBackslashByPdo()
    {
        if (config('database.default') === 'sqlite') {
            return '\\\\';
        }

        return '\\\\\\';
    }
}
