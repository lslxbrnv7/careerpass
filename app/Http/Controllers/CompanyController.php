<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $companies = Company::all();

        $array = [];

        foreach ($companies as $company) {

            $modifiedData['id'] = $company->id;
            $modifiedData['Company name'] = $company->company;
            $modifiedData['owner'] = $company->owner;
            $modifiedData['Jobs'] = $company->jobOffer->where('is_active', true)->values();
            $modifiedData['created_at'] = $company->created_at;
            $modifiedData['updated_at'] = $company->updated_at;

            $array[] =  [
                'Company' => $modifiedData
            ];
        }

        return $array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create([
            'company' => $request['company'],
            'owner' => $request['owner'],
        ]);

        return $company;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return ['errorMsg' => 'Company not found'];
        }

        $modifiedData['id'] = $company->id;
        $modifiedData['Company name'] = $company->company;
        $modifiedData['owner'] = $company->owner;
        $modifiedData['Jobs'] = $company->jobOffer->where('is_active', true)->values();
        $modifiedData['created_at'] = $company->created_at;
        $modifiedData['updated_at'] = $company->updated_at;

        return $modifiedData;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
