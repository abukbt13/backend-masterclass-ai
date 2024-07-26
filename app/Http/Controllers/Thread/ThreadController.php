<?php

namespace App\Http\Controllers\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function showActiveThread(){
        $user_id = Auth::user()->id;
        $thread = Thread::where('user_id', $user_id)->where('status','active')->get();
        return response()->json([
            'status' => 'success',
            'message' =>'threads retrieved successfully',
            'thread' => $thread,
        ]);
    }
    public function ActivateThread($id)
    {
        $user_id = Auth::user()->id;
        // Set all threads to 'closed' in one query
        Thread::where('status', '!=', 'closed')->where('user_id',$user_id)->update(['status' => 'closed']);

        // Find the specific thread by ID
        $newThread = Thread::find($id);

        // Check if the thread exists
        if (!$newThread) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thread not found',
            ], 404);
        }

        // Set the selected thread to 'active'
        $newThread->status = 'active';
        $newThread->update();

        return response()->json([
            'status' => 'success',
            'message' => 'Threads updated successfully',
            'thread' => $newThread,
        ]);
    }

    public function showThreads(){
        $user_id = Auth::user()->id;
        $thread = Thread::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'message' =>'threads retrieved successfully',
            'threads' => $thread,
        ]);
    }
}
