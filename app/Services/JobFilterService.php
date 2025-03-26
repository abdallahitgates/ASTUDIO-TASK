<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobFilterService
{
    protected $query;

    public function __construct()
    {
        $this->query = Job::query()->with(['languages', 'locations', 'categories', 'jobAttributeValues']);
    }

    public function applyFilters(Request $request)
    {
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            $this->applyCondition($filter);
        }

        return $this->query->get();
    }

    protected function applyCondition($condition)
    {
        $this->filterNumericFields($condition, ['salary_min', 'salary_max']);
        $this->filterBooleanFields($condition, ['is_remote']);
        $this->filterTextFields($condition, ['title', 'description', 'company_name']);
        $this->filterEnumFields($condition, ['job_type', 'status']);
        $this->filterDateFields($condition, ['published_at', 'created_at']);

        // Relation Filters (Languages, Locations, Categories)
        foreach (['languages' => 'name', 'locations' => 'city', 'categories' => 'name'] as $relation => $column) {
            $this->applyRelationFilters($condition, $relation, $column);
        }

        // Attribute Filters (EAV)
        $this->filterAttributes($condition);
    }

    /**
     * Numeric Fields: salary_min, salary_max (Supports =, !=, >, <, >=, <=)
     */
    private function filterNumericFields($condition, array $fields)
    {
        foreach ($fields as $field) {
            if (preg_match("/{$field}\s*([=!><]+)\s*(\d+)/", $condition, $matches)) {
                list(, $operator, $value) = $matches;
                $this->query->where($field, $operator, $value);
            }
        }
    }

    /**
     * Boolean Fields: is_remote (Supports =, !=)
     */
    private function filterBooleanFields($condition, array $fields)
    {
        foreach ($fields as $field) {
            if (preg_match("/{$field}\s*([=!]+)\s*(true|false|1|0)/i", $condition, $matches)) {
                list(, $operator, $value) = $matches;
                $booleanValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (int) $value;
                $this->query->where($field, $operator, $booleanValue);
            }
        }
    }

    /**
     * Text Fields: title, description, company_name (Supports =, !=, LIKE)
     */
    private function filterTextFields($condition, array $fields)
    {
        foreach ($fields as $field) {
            if (preg_match("/{$field}\s*([=!~]+)\s*\"(.*?)\"/", $condition, $matches)) {
                list(, $operator, $value) = $matches;

                switch ($operator) {
                    case '=':
                        $this->query->where($field, '=', $value);
                        break;
                    case '!=':
                        $this->query->where($field, '!=', $value);
                        break;
                    case '~':
                        $this->query->where($field, 'LIKE', "%{$value}%");
                        break;
                }
            }
        }
    }

    /**
     * Enum Fields: job_type, status (Supports =, !=, IN)
     */
    private function filterEnumFields($condition, array $fields)
    {
        foreach ($fields as $field) {
            if (preg_match("/{$field}\s*([=!]+)\s*([\w-]+)/", $condition, $matches)) {
                list(, $operator, $value) = $matches;
                $this->query->where($field, $operator, $value);
            }

            if (preg_match("/{$field}\s*IN\s*\(([^)]+)\)/", $condition, $matches)) {
                $valuesArray = array_map('trim', explode(',', $matches[1]));
                $this->query->whereIn($field, $valuesArray);
            }
        }
    }

    /**
     * Date Fields: published_at, created_at (Supports =, !=, >, <, >=, <=)
     */
    private function filterDateFields($condition, array $fields)
    {
        foreach ($fields as $field) {
            if (preg_match("/{$field}\s*([=!<>]+)\s*([\d-]+)/", $condition, $matches)) {
                list(, $operator, $value) = $matches;
                $this->query->whereDate($field, $operator, $value);
            }
        }
    }

    /**
     * Apply relational filters (Equality, HAS_ANY, IS_ANY, EXISTS)
     */
    private function applyRelationFilters($filter, $relation, $column)
    {
        if (preg_match("/{$relation}\s*=\s*([\w\s]+)/", $filter, $matches)) {
            $this->query->whereHas($relation, fn($q) => $q->where($column, trim($matches[1])));
        }

        if (preg_match("/{$relation}\s*HAS_ANY\s*\(([^)]+)\)/", $filter, $matches)) {
            $values = array_map('trim', explode(',', $matches[1]));
            $this->query->whereHas($relation, fn($q) => $q->whereIn($column, $values));
        }

        if (preg_match("/{$relation}\s*IS_ANY\s*\(([^)]+)\)/", $filter, $matches)) {
            $values = array_map('trim', explode(',', $matches[1]));
            $this->query->whereHas($relation, fn($q) => $q->whereIn($column, $values), '=', count($values));
        }

        if (preg_match("/{$relation}\s*EXISTS/", $filter)) {
            $this->query->whereHas($relation);
        }
    }

    /**
     * Attribute Filtering (EAV: Text, Number, Boolean, Select)
     */
    private function filterAttributes($condition)
    {
        if (preg_match('/attribute:(\w+)\s*([=!<>]+)\s*([\w\d,]+)/', $condition, $matches)) {
            list(, $attributeName, $operator, $value) = $matches;

            $this->query->whereHas('jobAttributeValues', function ($query) use ($attributeName, $operator, $value) {
                $query->whereHas('attribute', fn($q) => $q->where('name', $attributeName));

                if (is_numeric($value)) {
                    $query->whereRaw("CAST(value AS SIGNED) {$operator} ?", [$value]);
                } elseif (in_array($operator, ['=', '!='])) {
                    $query->where('value', $operator, $value);
                } elseif ($operator === 'LIKE') {
                    $query->where('value', 'LIKE', "%{$value}%");
                } elseif ($operator === 'IN') {
                    $query->whereIn('value', explode(',', $value));
                }
            });
        }
    }
}

