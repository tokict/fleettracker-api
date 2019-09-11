<?php

namespace App\Console\Commands;

use App\Models\BankTransfersDatum;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Issue;
use App\Models\MonetaryInput;
use App\Models\MonetaryOutput;
use App\Models\MonetaryOutputSource;
use App\Models\OdometerEntries;
use App\Models\Order;
use App\Models\PaymentProviderDatum;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Statuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all statuses';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Running status updates");

        //Issues







    }

}
