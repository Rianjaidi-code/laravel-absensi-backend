<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PermissionController extends Controller
{
    //index
    public function index(Request $request)
    {
        $permissions = Permission::with('user')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })->orderBy('id', 'desc')->paginate(10);
        return view('pages.permission.index', compact('permissions'));
    }

    //view
    public function show($id)
    {
        $permission = Permission::with('user')->find($id);
        return view('pages.permission.show', compact('permission'));
    }

    //edit
    public function edit($id)
    {
        $permission = Permission::with('user')->find($id);
        return view('pages.permission.edit', compact('permission'));
    }

    //update
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->is_approved = $request->is_approved;
        $str = $request->is_approved ? 'Disetujui' : 'Ditolak';
        $permission->save();
        $this->sendNotificationToUsers($permission->user_id, $request->is_approved ? 'Status izin anda adalah ' . $str  : 'Status izin anda adalah ' . $str);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function sendNotificationToUsers($userId, $message)
    {

        //Dapatkan FCM token user dari table users
        $user = User::find($userId);
        $token = $user->fcm_token;

        //Kirim notifikasi ke perangkat android
        $messaging = app('firebase.messaging');
        $notification = Notification::create('Status Izin', $message);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }
}
