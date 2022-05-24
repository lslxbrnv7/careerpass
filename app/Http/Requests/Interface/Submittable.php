<?php

namespace App\Http\Requests\Interface;

interface Submittable
{
    public function submit($model = null);
}
