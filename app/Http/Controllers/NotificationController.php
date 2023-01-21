<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NewStudentAssignedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Notifications;

class NotificationController extends Controller
{

    public function store(Request $request)
    {
        try{

            \Log::info($request['student']);
            \Log::info($request['teacher']);

            $teacher = $request['teacher'];
            $student = $request['student'];

            $email = (isset($teacher['email']))?$teacher['email']:'';

            $details = [
                'message' => 'A new student has been assigned to you.',
            ];

            //Notification::route('mail',$email)->notify(new NewStudentAssignedNotification($details));

            Notifications::create([
                'id'   => '993b6f5f-7e28-4fe6-a6a6-a031e221d31f',
                'type' => 'App\Notifications\NewStudentAssignedNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' =>$teacher['id'],
                'data' => json_encode($details),
                'created_at'=> now()
            ]);

           return true;

        }catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }


}
