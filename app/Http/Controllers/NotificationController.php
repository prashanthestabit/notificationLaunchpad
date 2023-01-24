<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NewStudentAssignedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Notifications;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function store(Request $request)
    {
        try{
            $header = $request->header('Authorization');
            if(empty($header))
            {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $studentUrl = config('app.student_url').'/user/'.$request->input('user_id');

            $student = Http::withHeaders([
                'Authorization' => $header,
                ])->get($studentUrl);


            if($student->status() === 200 && $student->json('user'))
            {
                $studentDetails = $student->json('user');
                $teacherDetails = User::find($request->input('teacher_id'));


                $teacherDetails->notify(new NewStudentAssignedNotification($studentDetails));
                Log::info("Notification Send");
            }

           return true;

        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => __('messages.error')], 500);
        }
    }


}
