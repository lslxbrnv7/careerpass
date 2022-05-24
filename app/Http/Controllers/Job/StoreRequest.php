<?php

namespace App\Http\Controllers\Job;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Interface\Submittable;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class StoreRequest extends FormRequest implements Submittable
{
    public function rules()
    {
        return [
            "title" => [
                "required",
                "string",
                "max:255",
            ],
            "company" => [
                "required",
                "string",
                "max:255",
            ],
            "wysiwyg" => [
                "required",
                "string",
                "max:65535"
            ],
            'owner' => [
                'required',
                'string',
                'max:255'
            ],
            "starts_at" => [
                "required",
                "date",
                "before:expires_at"
            ],
            "expires_at" => [
                "required",
                "date",
                "after:starts_at"
            ],
            "is_active" => [
                "required",
                "boolean"
            ],
        ];
    }

    public function submit($model = null)
    {
        $validated = $this->validated();
        if (!$model) {
            $model = new Job();
        }
        $model->fill([
            'title' => $validated['title'],
            'company' => $validated['company'],
            'wysiwyg' => $validated['wysiwyg'],
            'starts_at' => $validated['starts_at'],
            'expires_at' => $validated['expires_at'],
            'is_active' => $validated['is_active']
        ])->save();
        DB::statement("UPDATE `job` set `owner` = '{$validated['owner']}' where id={$model->id}");
        $model->refresh();
        return $model;
    }
}
