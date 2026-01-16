<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Core class of repository
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model An instance of the Eloquent Model
     */
    protected $model;

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'DESC')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($perPage = 20)
    {
        return $this->model->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update($model, $data)
    {
        $model->update($data);

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($model)
    {
        return $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function clearCache(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->model->count();
    }


    /**
     * {@inheritdoc}
     */
    public function advancedGet(array $data): LengthAwarePaginator|Collection
    {
        $query = $this->model->query();

        if (isset($data['with'])) {
            $query->with($data['with']);
        }

        if (isset($data['select'])) {
            $query->select($data['select']);
        }

        if (isset($data['with_counts']) && count($data['with_counts']) > 0) {
            foreach ($data['with_counts'] as $withCount) {
                if (!isset($withCount['relation']) || !isset($withCount['column'])) {
                    continue;
                }

                $query->withCount($withCount['relation'], $withCount['column']);
            }
        }

        if (isset($data['with_sums']) && count($data['with_sums']) > 0) {
            foreach ($data['with_sums'] as $withSum) {
                if (!isset($withSum['relation']) || !isset($withSum['column'])) {
                    continue;
                }

                $query->withSum($withSum['relation'], $withSum['column']);
            }
        }

        if (isset($data['order_by']) && count($data['order_by']) === 2) {
            if (
                isset($data['order_by']['column'])
                && $data['order_by']['column']
                && isset($data['order_by']['type'])
                && $data['order_by']['type']
            ) {
                $query->orderBy($data['order_by']['column'], $data['order_by']['type']);
            }
        }

        if (isset($data['conditions'])) {
            $this->queryByConditions($query, $data['conditions']);
        }

        if (isset($data['with_trashed']) && $data['with_trashed']) {
            $query->withTrashed();
        }

        if (isset($data['only_trashed']) && $data['only_trashed']) {
            $query->onlyTrashed();
        }

        if (isset($data['limit']) && $data['limit']) {
            $query->limit($data['limit']);
        }

        if (isset($data['pagination'])) {
            return $query->paginate($data['pagination']);
        }

        return $query->get();
    }

    /**
     * {@inheritdoc}
     */
    public function advancedGetFirst(array $data): ?Model
    {
        $query = $this->model->query();
        if (isset($data['with'])) {
            $query->with($data['with']);
        }

        if (isset($data['select'])) {
            $query->select($data['select']);
        }

        if (isset($data['with_count']) && count($data['with_count']) > 0) {
            $query->withCount($data['with_count']);
        }

        if (isset($data['with_sums']) && count($data['with_sums']) > 0) {
            foreach ($data['with_sums'] as $withSum) {
                if (!isset($withSum['relation']) || !isset($withSum['column'])) {
                    continue;
                }

                $query->withSum($withSum['relation'], $withSum['column']);
            }
        }

        if (isset($data['order_by']) && count($data['order_by']) === 2) {
            if (
                isset($data['order_by']['column'])
                && $data['order_by']['column']
                && isset($data['order_by']['type'])
                && $data['order_by']['type']
            ) {
                $query->orderBy($data['order_by']['column'], $data['order_by']['type']);
            }
        }

        if (isset($data['with_trashed']) && $data['with_trashed']) {
            $query->withTrashed();
        }

        if (isset($data['only_trashed']) && $data['only_trashed']) {
            $query->onlyTrashed();
        }

        if (isset($data['conditions'])) {
            $this->queryByConditions($query, $data['conditions']);
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     */
    public function queryByConditions(Builder &$query, array $data): void
    {
        if (isset($data['where'])) {
            $query->where($data['where']);
        }

        if (isset($data['or_where'])) {
            $query->orWhere($data['or_where']);
        }

        if (isset($data['where_in'])) {
            foreach ($data['where_in'] as $key => $values) {
                $query->whereIn($key, $values);
            }
        }

        if (isset($data['where_not_in'])) {
            foreach ($data['where_not_in'] as $key => $values) {
                $query->whereNotIn($key, $values);
            }
        }

        if (isset($data['where_like'])) {
            foreach ($data['where_like'] as $key => $condition) {
                $query->where($key, 'like', $condition);
            }
        }

        if (isset($data['where_has'])) {
            foreach ($data['where_has'] as $key => $condition) {
                $query->whereHas($key, $condition);
            }
        }

        if (isset($data['where_doesnt_have'])) {
            foreach ($data['where_doesnt_have'] as $key => $condition) {
                $query->whereDoesntHave($key, $condition);
            }
        }

        if (isset($data['doesnt_have'])) {
            foreach ($data['doesnt_have'] as $condition) {
                $query->doesntHave($condition);
            }
        }
    }
}
