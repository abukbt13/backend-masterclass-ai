<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\chatRequest;
use App\Models\Chat;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
   public function asKQuestion(chatRequest $request){

       $data = $request->all();
        $user_id = Auth::user()->id;
       if ($data['thread_id']== 0) {
//           echo "nill";
//           dd($data);
//            Perfor== 'null'm actions if thread_id is null
            $newthread = new Thread();
            $newthread->user_id = $user_id;
            $newthread->name = $data['question'];
            $newthread->status = 'active';
            $newthread->save();
        }
        else{
//                       echo "away";
//           dd($data);
//            dd($data['thread_id']);
            $newthread = Thread::where('user_id',$user_id)->where('id',$data['thread_id'])->first();
            $newthread->status = 'active';
            $newthread->update();
        }


       $question = $data['question'];
//       $result = OpenAI::chat()->create([
//           'model' => 'gpt-3.5-turbo',
//           'messages' => [
//               ['role' => 'user', 'content' => $question],
//           ],
//       ]);

       $chat = new Chat();
       $chat['question'] = $question;
       $chat['thread_id'] = $newthread['id'];
       $chat['user_id'] = $user_id;
//       $chat['response'] = "$result->choices[0]->message->content";
       $chat['response'] = "expected message";
       $chat->save();
       return response()->json([
           'status' => 'success',
           'message' => 'message sent successfully',
           'chats' => $chat
       ]);

   }
   public function showQuestion(){
       $thread = Thread::where('status','active')->first();
       $chats = Chat::where('thread_id',$thread['id'])->get();
       return response()->json([
           'status' => 'success',
           'message' => 'chats fetched successfully',
           'chats' => $chats
       ]);
   }
}
