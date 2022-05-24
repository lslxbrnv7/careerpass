<?php

namespace App\Observers;

use Illuminate\Support\Str;
use Exception;

class SlugObserver
{
    protected $attribute;
    protected $unique = true;

    public function creating($model)
    {
        $this->updateSlug($model);
    }

    public function updating($model)
    {
        $this->updateSlug($model);
    }

    private function updateSlug($model, $suffix = 0)
    {
        $slug = $this->calculateSlug($model, $suffix);
        if (!$this->unique) {
            $model->slug = $slug;
            return;
        }

        $exists = $model::where('slug', $slug)->where('id', '!=', $model->id)->exists();

        if (!$exists) {
            $model->slug = $slug;
            return;
        }

        $this->updateSlug($model, $suffix ? $suffix+1 : 1);
    }

    private function calculateSlug($model, $suffix = 0)
    {
        if (!$model->{$this->attribute}) {
            throw new Exception("Model doesnt have attribute $this->attribute to generate a slug from");
        }

        $slug = Str::slug($model->{$this->attribute});
        if (!$suffix) {
            return $slug;
        }
        return "$slug-$suffix";
    }
}
