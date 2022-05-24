<?php

namespace App\Http\Controllers\Job;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Interface\Searchable;
use App\Models\Job;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest implements Searchable
{
    public function rules()
    {
        return [
            "title" => [
                "nullable",
                "string",
                "max:255",
            ],
            "company" => [
                "nullable",
                "string",
                "max:255",
            ],
            "is_active" => [
                "nullable",
                "boolean"
            ],
            "status" => [
                "nullable",
                Rule::in([
                    Job::STATUS_ACTIVE,
                    Job::STATUS_EXPIRED,
                    Job::STATUS_PENDING
                ])
            ]
        ];
    }

    public function search()
    {
        $query = Job::query();

        $query->when($this->input('title'), function ($query, $title) {
            return $query->where('title', 'LIKE', "%{$title}%");
        });

        $query->when($this->input('company'), function ($query, $company) {
            return $query->where('company', 'LIKE', "%{$company}%");
        });

        if (!is_null($this->input('is_active'))) {
            $query->where('is_active', $this->input('is_active'));
        }

        $query->when($this->input('status'), function ($query, $status) {
            return $query->where('status', $status);
        });


        return $query->paginate();
    }

}
