<?php

namespace App\Observers;


use App\Models\Assignment;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleObserver
{


    /**
     * Listen to the Vehicle deleting event.
     *
     * @param  Vehicle $vehicle
     * @return void
     */
    public function deleted(Vehicle $vehicle)
    {
        $vehicle->deleted_by = Auth::user()->id;

        //Remove vehicle from any other resources using it's id

        //Assigments
        $vehicle->assignments()->each(function ($item) {
            $item->delete();
        });
        //Comments
        $vehicle->comments()->each(function ($item) {
            $item->delete();
        });
        //Issues
        $vehicle->issues()->each(function ($item) {
            $item->delete();
        });
        //Services
        $vehicle->services()->each(function ($item) {
            $item->delete();
        });
        //Subscriptions

        $vehicle->subscriptions()->each(function ($item) {
            $item->delete();
        });


    }

    public function creating(Vehicle $vehicle)
    {
        if(Auth::user()) {
            $vehicle->created_by = Auth::user()->id;
        }


    }

    public function saved(Vehicle $vehicle)
    {

        $this->assignUpdate($vehicle);


    }


    private function assignUpdate(Vehicle $vehicle)
    {
        if (!$vehicle->operator_id) {
            //Deassign all assignments for this vehicle
            $this->deactivateAll($vehicle);
            return;

        }


        $oldAssignment = Assignment::where('item_id', $vehicle->id)->where('type', 'vehicle')
            ->where('contact_id', $vehicle->operator_id)->whereNull('ended_at')->get()->first();

        if ($oldAssignment) {
            //Contact is already assigned to this vehicle
            return;
        } else {
            //Create new assignment but end all previous ones for the vehicle
            $this->deactivateAll($vehicle);
            //Create new assignment
            $newAssignment = new Assignment();
            $newAssignment->item_id = $vehicle->id;
            $newAssignment->type = 'vehicle';
            $newAssignment->contact_id = $vehicle->operator_id;
            $newAssignment->company_id = $vehicle->company_id;
            $newAssignment->started_at = date("Y-m-d H:i:s", time());
            $newAssignment->odo_start = $vehicle->getOdometerState();
            $newAssignment->save();
        }


    }

    private function deactivateAll(Vehicle $vehicle)
    {
        $vehicleAssignments = Assignment::where('item_id', $vehicle->id)->whereNull('ended_at')->where('type', 'vehicle')
            ->get();

        if ($vehicleAssignments->count()) {
            foreach ($vehicleAssignments as $assignment) {
                //Deactivate assignment with current time and odo
                $assignment->odo_end = $vehicle->getOdometerState();
                $assignment->ended_at = date("Y-m-d H:i:s", time());
                $assignment->save();
            }
        }
    }

}