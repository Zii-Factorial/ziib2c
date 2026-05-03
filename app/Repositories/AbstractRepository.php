<?php

namespace App\Repositories;

use App\Criteria\LatestSortCriteria;
use App\Criteria\RequestCriteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Prettus\Repository\Contracts\Criteria;
use Prettus\Repository\Eloquent\Repository as BaseRepo;
use Prettus\Repository\Exceptions\RepositoryException;

abstract class AbstractRepository extends BaseRepo
{
    protected bool $skipLatestSortCriteria = false;

    public function __construct()
    {
        /** @var Model $model */
        $model = app($this->model());

        parent::__construct($model);
    }

    /**
     * @return class-string<Model>
     */
    abstract public function model(): string;

    /**
     * @return class-string
     */
    public function getDataClass(): string
    {
        return $this->model->getDataClass();
    }

    /**
     * @return class-string
     */
    public function getStoreDataClass(): string
    {
        return $this->model->getStoreDataClass();
    }

    /**
     * @return class-string
     */
    public function getUpdateDataClass(): string
    {
        return $this->model->getUpdateDataClass();
    }

    /**
     * @throws RepositoryException
     */
    public function boot(): void
    {
        if (! $this->skipLatestSortCriteria) {
            $this->pushCriteria(app(LatestSortCriteria::class));
        }
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return $this
     *
     * @throws RepositoryException
     */
    public function pushCriteriaIf(bool $condition, Criteria $criteria): self
    {
        if ($condition) {
            $this->pushCriteria($criteria);
        }

        return $this;
    }

    protected function syncMedia($model, array $attachments, string $collectionName = 'default'): void
    {
        $uploadedIds = collect($attachments)->pluck('id')->toArray();
        $keepMedia = $model->getMedia($collectionName)->whereIn('id', $uploadedIds)->toArray();

        $model->clearMediaCollectionExcept($collectionName, $keepMedia);

        foreach ($attachments as $attachment) {
            if ($attachment instanceof UploadedFile) {
                $model->addMedia($attachment)->toMediaCollection();
            }
        }
    }
}
