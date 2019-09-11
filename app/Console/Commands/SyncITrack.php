<?php

namespace App\Console\Commands;

use App\Models\BankTransfersDatum;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\Donor;
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

class SyncITrack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncITrack {company?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data with ITrack';


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
        $this->info("Running sync");
        $c = $this->argument('company');
        $companies = Company::whereNotNull('itrack_token')->get();


        $lastOdo = OdometerEntries::take(1)->first();


        $client = new Client();

        foreach ($companies as $company) {
            if (isset($c) && $company->id !== $c || !isset($company->itrack_token)) {
                continue;
            }
            $itrackDrivers = [];
            try {
                $itrackDrivers = json_decode($client->request('GET', 'http://api.itrack.bg/drivers',
                    ['query' => 'token=' . $company->itrack_token])->getBody()->getContents());
            } catch (\Exception $e) {

            }
            //Check for new drivers and add to contacts

            foreach ($itrackDrivers as $d) {

                $imported = Contact::where('itrack_id', $d->id)->get();
                if ($imported->count()) {
                    //Skip if it has been already imported
                    continue;
                }
                $nameParts = explode(" ", $d->name, 2);

                $contact = new Contact();
                $contact->first_name = isset($nameParts[0]) ? trim($nameParts[0]) : "";
                $contact->last_name = isset($nameParts[1]) ? trim($nameParts[1]) : "";
                $contact->mobile_phone = $d->phone;
                $contact->itrack_id = $d->id;
                $contact->save();

            }

            try {
                $itrackVehicles = json_decode($client->request('GET', 'http://api.itrack.bg/vehicles',
                    ['query' => 'token=' . $company->itrack_token])->getBody()->getContents());
            } catch (\Exception $e) {

            }

            //Check if there are new vehicles
            foreach ($itrackVehicles as $v) {
                $imported = Vehicle::where('itrack_id', $v->id)->get();
                if ($imported->count()) {
                    //Skip if it has been already imported
                    continue;
                }

                $nameParts2 = explode(" ", $v->name, 2);
                $maker = null;
                $model = null;
                if (isset($nameParts2[0])) {
                    $m = VehicleMaker::where('name', trim($nameParts2[0]))->get();
                    if ($m->count()) {
                        $maker = $m->first()->id;

                        if (isset($nameParts2[1])) {
                            $m = VehicleModel::where('name', trim($nameParts2[1]))->get();
                            if ($m->count()) {
                                $model = $m->first()->id;
                            }
                        }
                    }
                }

                $op = Contact::where('itrack_id', $v->driver_id_current)->get()->first();
                //Import vehicle
                $veh = new Vehicle();
                $veh->name = $v->name;
                $veh->plate = $v->plate;
                $veh->maker_id = $maker;
                $veh->model_id = $model;
                $veh->itrack_id = $v->id;
                $veh->status = 'active';
                $veh->operator_id = $op ? $op->id : null;
                $veh->company_id = $company->id;
                $veh->body = 'other';

                $veh->save();

            }

            $date = date("Y-m-d", time());
            $beginOfDay = strtotime("midnight", time());
            $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;


            //Get all linked vehicles and add their odo entries
            $odos = json_decode($client->request('GET', 'http://api.itrack.bg/canbus', [
                'query' => [
                    'token' => $company->itrack_token,
                    'timestamp_a' => $beginOfDay,
                    'timestamp_b' => $endOfDay
                ]
            ])->getBody()->getContents());


            //Update odos
            foreach ($odos as $odo) {

                $vehicle = Vehicle::where('itrack_id', $odo->car_id)->get()->first();
                $lastOdo = OdometerEntries::where('vehicle_id', $vehicle->id)->get()->last();

                if (isset($lastOdo->odo_end) && $odo->odometer_can_end == $lastOdo->odo_end) {
                    return;
                }

                $entry = OdometerEntries::create([
                    'vehicle_id' => $vehicle->id,
                    'date' => $date,
                    'odo_end' => $odo->odometer_can_end,
                ]);
            }


        }


    }

}
