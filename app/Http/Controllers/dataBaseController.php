<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\transaction;
use Illuminate\Http\Request;

class dataBaseController extends Controller
{
    //
    public function saveToDb(Request $request)
    {
        // Create a new message instance
        try {
            $newTransaction = new Transaction();
            $newTransaction->driver_id = $request->input('driver_id');
            $newTransaction->passenger_id = $request->input('passenger_id');
            $newTransaction->date = $request->input('date');
            $newTransaction->fare_amount = $request->input('fare_amount');
            $newTransaction->landmark = $request->input('landmark');
            $newTransaction->pickup_point = $request->input('pickup_point');
            $newTransaction->dropoff_point = $request->input('dropoff_point');
            $newTransaction->notes = $request->input('notes');
            $newTransaction->status = $request->input('status');
            $newTransaction->save();

            return response()->json(['message' => 'Transaction saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOrSaveToDB(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other fields you want to validate
        ]);

        // Retrieve the name from the request
        $name = $request->input('name');

        // Check if the passenger already exists
        $passenger = Passenger::where('name', $name)->first();

        if ($passenger) {
            // Passenger already exists, return the existing ID
            return response()->json(['id' => $passenger->passenger_id]);
        } else {
            // Passenger does not exist, create a new record
            $newPassenger = Passenger::create([
                'name' => $name,
                // Add other fields you want to set
            ]);

            // Return the new ID
            return response()->json(['id' => $newPassenger->passenger_id]);
        }
    }
}
