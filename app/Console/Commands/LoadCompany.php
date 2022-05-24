<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Models\Company;

class LoadCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Company';

    /**
     * Execute the console command.
     *
     * @return Array
     */
    public function handle()
    {

        $datas = [
            [
                'company' => 'Miller',
                'owner' => 'J.M'
            ],
            [
                'company' => 'Accenture',
                'owner' => 'A.C'
            ],
            [
                'company' => 'GoGreen',
                'owner' => 'G.G'
            ],
            [
                'company' => 'CoDev',
                'owner' => 'C.D'
            ],
        ];

        foreach ($datas as $data) {
            Company::create([
                'company' => $data['company'],
                'owner' => $data['owner'],
            ]);
        }
    }
}
