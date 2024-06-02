<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //checkin
    public function checkin(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //save new attendance
        $attendance = new Attendance;
        $attendance->user_id = auth()->user()->id;
        $attendance->date = date('Y-m-d');
        $attendance->time_in = date('H:i:s');
        $attendance->latlon_in = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'success' => true,
            'message' => 'Checkin successfully.',
            'attendance' => $attendance
        ], 200);
    }

    //checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        //check if attendance not found

        if (!$attendance) {
            return response([
                'success' => false,
                'message' => 'Checkin first.'
            ], 400);
        }

        //save checkout
        $attendance->time_out = date('H:i:s');
        $attendance->latlon_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'success' => true,
            'message' => 'Checkout successfully.',
            'attendance' => $attendance
        ], 200);
    }

    //check is checked in
    public function isCheckedIn(Request $request)
    {
        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        $isCheckout = $attendance ? $attendance->time_out : false;

        return response([
            'success' => true,
            'is_checked_in' => $attendance ? true : false,
            'is_checked_out' => $isCheckout ? true : false
        ], 200);
    }
}
