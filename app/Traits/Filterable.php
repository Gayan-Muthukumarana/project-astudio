<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait Filterable
{
    /**
     * @param Builder $query
     * @param Request $request
     * @param array $allowedFilters
     * @return Builder
     */
    public function scopeApplyFilters(Builder $query, Request $request, array $allowedFilters): Builder
    {
        $filters = $request->get('filters', []);

        if (!is_array($filters)) {
            throw new InvalidArgumentException('Invalid filter format. Expected an array.');
        }

        foreach ($filters as $field => $value) {
            if (!in_array($field, $allowedFilters)) {
                continue;
            }

            if (is_array($value)) {
                if (!isset($value['operator']) || !isset($value['value'])) {
                    throw new InvalidArgumentException("Filter structure must include 'operator' and 'value'.");
                }
                $operator = strtoupper($value['operator']);
                $filterValue = $value['value'];
            } else {
                $operator = '=';
                $filterValue = $value;
            }

            switch ($operator) {
                case '=':
                    $query->where($field, '=', $filterValue);
                    break;
                case '>':
                    $query->where($field, '>', $filterValue);
                    break;
                case '<':
                    $query->where($field, '<', $filterValue);
                    break;
                case 'LIKE':
                    $query->where($field, 'LIKE', '%' . $filterValue . '%');
                    break;
                default:
                    throw new InvalidArgumentException("Invalid operator: $operator");
            }
        }

        return $query;
    }
}
