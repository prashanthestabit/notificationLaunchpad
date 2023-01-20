<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NewStudentAssignedNotification;
use Illuminate\Support\Facades\Notification;

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
            
            Notification::route('mail',$email)->notify(new NewStudentAssignedNotification($details));

           return true;

        }catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }
    
    
}
