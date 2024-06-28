<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseRepository
{
    protected const MODEL = '';

    public function findOnByOr404(array $args): Model
    {
        $model = $this->findOneBy($args);

        return $model !== null ? $model :
            throw new HttpException(404, sprintf('Model of type %s not found',static::MODEL));
    }

    public function findOneBy(array $args): ?Model
    {
        $builder = $this->getBuilder();

        foreach ($args as $field => $value) {
            $builder->where($field, $value);
        }

        return $builder->first();
    }

    public function findAll(): Collection
    {
        $builder = $this->getBuilder();

        return $builder->get();
    }

    public function getBuilder(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var Model $model */
        $model = new (static::MODEL);

        return $model->newModelQuery();
    }
}
