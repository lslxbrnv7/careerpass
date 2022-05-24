<?php

namespace App\Observers;

class JobSlugObserver extends SlugObserver
{
    protected $attribute = 'title';
}
