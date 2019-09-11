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
use App\Models\Notification;
use App\Models\OdometerEntries;
use App\Models\Order;
use App\Models\PaymentProviderDatum;
use App\Models\Reminder;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\VehicleMaker;
use App\Models\VehicleModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class Notify extends Command
{

    private $dueSoonMails = [];
    private $overdueMails = [];
    private $serviceTypes;
    private $renewalTypes;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out all notifications';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->serviceTypes = [
            "engine_oil" => "Масло на двигател",
            "transmission_oil" => "Масло на скоростна кутия",
            "antifreeze" => "Антифриз",
            "hydraulic_fluid" => "Хидравлична течнос",
            "differential_oil" => "Масло на диференциал",
            "brake_fluid" => "Спирачна течност",
            "timing_belt" => "Ангренажен ремък",
            "timing_chain" => "Ангренажни вериги",
            "spark_plug" => "Свещи",
            "water_pump" => "Водна помпа",
            "track_belt" => "Пистов ремък",
            "thermostat" => "Термостат",
            "oil_filter" => "Маслен филтър",
            "air_filter" => "Въздушен филтър",
            "fuel_filter" => "Горивен филтър",
            "transmission_oil_filter" => "Филтър маслен на ск. Кутия",
            "cockpit_filter" => "Филтър на купето",
            "front_brake_rotor" => "Предни дискове",
            "front_brake_pads" => "Предни накладки",
            "rear_brake_rotor" => "Задни дискове",
            "rear_brake_pads" => "Задни накладки",
            "cylinders_and_seal_machines" => "Цилиндри/уплътнения на апарати",
            "front_shock_absorber" => "Предни амортисьори",
            "rear_shock_absorber" => "Задни амортисьори",
            "rear_left_upper_wishbone" => "Заден, ляв, горен носач",
            "front_right_lower_wishbone" => "Преден, десен, долен носач",
            "right_stabilizer_link" => "Биалетка дясна",
            "left_stabilizer_link" => "Биалетка лява",
            "left_tie_rod_end" => "Кормилен накрайник ляв",
            "right_tie_rod_end" => "Кормилен накрайник десен",
            "rear_left_lower_wishbone" => "Заден, ляв, долен носач",
            "front_right_upper_wishbone" => "Преден, десен, горен носач",
            "rear_right_upper_wishbone" => "Заден, десен, горен носач",
            "front_left_lower_wishbone" => "Преден, ляв, долен носач",
            "front_left_upper_wishbone" => "Преден, ляв, горен носач",
            "rear_right_lower_wishbone" => "Заден, десен, долен носач",
            "front_wipers" => "Предни чистачки",
            "rear_wiper" => "Задна чистачка",
            "battery" => "Акумулатор",
            "fuses" => "Бушони",
            "lights" => "Крушки",
            "alternator" => "Алтернатор (генератор)",
            "air_conditioning_compressor" => "Компресор на климатика",
            "clutch" => "Съединител",
            "flywheel" => "Маховик",
            "catalytic_converter" => "Катализатор",
            "diesel_particulate_filter" => "Филтър за твърди частици (ДПФ/DPF)"
        ];

        $this->renewalTypes = [
            "annual_technical_inspection" => "Годишен технически преглед",
            "vignette_bg" => "Винетка (България)",
            "motor_vehicle_tax" => "Данък МПС",
            "vehicle_liability_insurance" => "Застраховка ГО",
            "vehicle_insurance" => "Застраховка Автокаско"
        ];

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("Scanning for notification triggers");

        //Get all reminders
        $reminders = Reminder::get();

        foreach ($reminders as $reminder) {
            $services = $reminder::getAlerts('service');
            $renewals = $reminder::getAlerts('renewal');

            //Do the due soon first
            foreach ($services['due_soon'] as $service) {
                $data = [];
                $data['type'] = 'service';
                $data['trigger_type'] = $service["trigger_type"];
                $data['reminder'] = $service['reminder'];
                $data['subscribers'] = $service['reminder']->subscribers;
                $data['vehicle'] = $service['vehicle'];
                $data['task'] = $this->serviceTypes[$service['reminder']->service_type->name];
                $data['group'] = isset($service['vehicle']->group) ? $service['vehicle']->group : '';
                $data['operatorName'] = isset($service['vehicle']->contact) ? $service['vehicle']->contact->first_name
                    . ' ' . $service['vehicle']->contact->last_name : '';
                $data['status'] = 'Due soon';

                if ($service['type'] == 'time') {
                    $data['trigger'] = date("Y-m-d", $service['trigger']);
                    $data['difference'] = $this->secondsToTime($service['trigger'] - time());
                } else {
                    $data['trigger'] = $service['trigger'];
                    $data['difference'] = $service['trigger'] - $service['vehicle']->getOdometerState();
                }
                $this->dueSoonMails[] = $data;
            }

            foreach ($services['overdue'] as $service) {

                $data = [];
                $data['type'] = 'service';
                $data['trigger_type'] = $service["trigger_type"];
                $data['reminder'] = $service['reminder'];
                $data['subscribers'] = $service['reminder']->subscribers;
                $data['vehicle'] = $service['vehicle'];
                $data['task'] = $service['reminder']->service_type;
                $data['group'] = isset($service['vehicle']->group) ? $service['vehicle']->group : null;
                $data['operatorName'] = isset($service['vehicle']->contact) ? $service['vehicle']->contact->first_name
                    . ' ' . $service['vehicle']->contact->last_name : null;
                $data['status'] = 'Overdue';

                if ($service['type'] == 'time') {
                    $data['trigger'] = date("Y-m-d", $service['trigger']);
                    $data['difference'] = $this->secondsToTime(time() - $service['trigger']);
                } else {
                    $data['trigger'] = $service['trigger'];
                    $data['difference'] = $service['vehicle']->getOdometerState() - $service['trigger'];
                }
                $this->overdueMails[] = $data;
            }


            //RENEWALS //Do the due soon first
            foreach ($renewals['due_soon'] as $renewal) {

                $data = [];
                $data['type'] = 'renewal';
                $data['reminder'] = $renewal['reminder'];
                $data['trigger_type'] = $renewal["trigger_type"];
                $data['subscribers'] = $renewal['reminder']->subscribers;
                $data['vehicle'] = $renewal['vehicle'];
                $data['task'] = $this->renewalTypes[$renewal['reminder']->renewal_type->name];

                $data['group'] = isset($renewal['vehicle']->group) ? $renewal['vehicle']->group : '';
                $data['operatorName'] = isset($renewal['vehicle']->contact) ? $renewal['vehicle']->contact->first_name
                    . ' ' . $renewal['vehicle']->contact->last_name : '';
                $data['status'] = 'Due soon';

                if ($renewal['type'] == 'time') {
                    $data['trigger'] = date("Y-m-d", $renewal['trigger']);
                    $data['difference'] = $this->secondsToTime($renewal['trigger'] - time());
                } else {
                    $data['trigger'] = $renewal['trigger'];
                    $data['difference'] = $renewal['trigger'] - $renewal['vehicle']->getOdometerState();
                }
                $this->dueSoonMails[] = $data;
            }

            foreach ($renewals['overdue'] as $renewal) {

                $data = [];
                $data['type'] = 'renewal';
                $data['trigger_type'] = $renewal["trigger_type"];
                $data['reminder'] = $renewal['reminder'];
                $data['subscribers'] = $renewal['reminder']->subscribers;
                $data['vehicle'] = $renewal['vehicle'];
                $data['task'] = $this->renewalTypes[$renewal['reminder']->renewal_type->name];
                $data['group'] = isset($renewal['vehicle']->group) ? $renewal['vehicle']->group : '';
                $data['operatorName'] = isset($renewal['vehicle']->contact) ? $renewal['vehicle']->contact->first_name
                    . ' ' . $renewal['vehicle']->contact->last_name : '';
                $data['status'] = 'Overdue';

                if ($renewal['type'] == 'time') {
                    $data['trigger'] = date("Y-m-d", $renewal['trigger']);
                    $data['difference'] = $this->secondsToTime(time() - $renewal['trigger']);
                } else {
                    $data['trigger'] = $renewal['trigger'];
                    $data['difference'] = $renewal['vehicle']->getOdometerState() - $renewal['trigger'];
                }
                $this->overdueMails[] = $data;
            }

        }

        $this->sendEmails();
    }

    function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a');
    }

    private function sendEmails()
    {
        foreach ($this->dueSoonMails as &$mail) {
            foreach ($mail['subscribers'] as $sub) {

                if ($this->wasSent($mail['reminder'], $sub->contact, $mail['trigger_type'], $mail['vehicle'])) {
                    continue;
                }
                $this->info($sub->contact->email);
                Mail::send('emails.reminders.due_soon', $mail, function ($m) use ($sub, $mail) {
                    $m->to($sub->contact->email)->subject('[Приближава] ' . $mail['vehicle']->name . ': ' . $mail['task']);
                });
                $this->info('Sent mail to : ' . $sub->contact->email . ' for ' . $mail['vehicle']->name . ' about ' . $mail['task']);

                //Mark as sent
                Notification::create([
                    'type' => $mail['type'],
                    'sent_at' => date("Y-m-d H:i:s"),
                    'text' => '[Overdue] ' . $mail['vehicle']->name . ': ' . $mail['task'],
                    'item_id' => $mail['reminder']->id,
                    'subtype' => $mail['trigger_type'],
                    'by_email' => 1,
                    'user_id' => isset($sub->contact) && isset($sub->contact->user) ? $sub->contact->user->id : null,
                    'contact_id' => $sub->contact->id,
                    'vehicle_id' => $mail['vehicle']['id']
                ]);
            }
        }

        foreach ($this->overdueMails as &$mail) {
            foreach ($mail['subscribers'] as $sub) {
                if ($this->wasSent($mail['reminder'], $sub->contact, $mail['trigger_type'], $mail['vehicle'])) {
                    continue;
                }
                $this->info($sub->contact->email);
                Mail::send('emails.reminders.overdue', $mail, function ($m) use ($sub, $mail) {
                    $m->to($sub->contact->email)->subject('[Просрочен] ' . $mail['vehicle']->name . ': ' . __('labels.'.$mail['type'].'_types.'.$mail['task']->name));
                });
                $this->info('Sent mail to : ' . $sub->contact->email . ' for ' . $mail['vehicle']->name . ' about ' . $mail['task']->name);
                //Mark as sent
                Notification::create([
                    'type' => $mail['type'],
                    'sent_at' => date("Y-m-d H:i:s"),
                    'text' => '[Overdue] ' . $mail['vehicle']->name . ': ' . $mail['task']->name,
                    'item_id' => $mail['reminder']->id,
                    'subtype' => $mail['trigger_type'],
                    'by_email' => 1,
                    'user_id' => isset($sub->contact) && isset($sub->contact->user) ? $sub->contact->user->id : null,
                    'contact_id' => $sub->contact->id,
                    'vehicle_id' => $mail['vehicle']['id']
                ]);
            }
        }
    }

    function wasSent($item, $contact, $trigger_type, $vehicle)
    {
        $type = isset($item->service_type_id) ? 'service' : 'renewal';
        $note = Notification::where('type', $type)->where('item_id', $item->id)->where('vehicle_id', $vehicle->id)
            ->where('subtype', $trigger_type)->where('contact_id', $contact->id)->get()->count();

        return $note == 0 ? false : true;
    }

}
