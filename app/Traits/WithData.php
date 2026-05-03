<?php

namespace App\Traits;

use Spatie\LaravelData\WithData as WithDataTrait;

trait WithData
{
    use WithDataTrait;

    /**
     * @return class-string
     */
    public function getDataClass(): string
    {
        return $this->dataClass;
    }

    /**
     * @return class-string
     */
    public function getStoreDataClass(): string
    {
        return $this->storeDataClass ?? $this->dataClass;
    }

    /**
     * @return class-string
     */
    public function getUpdateDataClass(): string
    {
        return $this->updateDataClass ?? $this->dataClass;
    }
}
