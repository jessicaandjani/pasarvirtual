<?php

namespace App\Http\Controllers;

use App\UserFeedback;
use Illuminate\Http\Request;

class UserFeedbackController extends Controller
{
    public function addFeedback(Request $request) {
    	$feedback = new UserFeedback();
        $feedback->order_id = $request->order_id;
    	$feedback->rating = $request->rating;
        $feedback->save();
        //get feedback id after insert the feedback
        $feedback_id = $feedback->id;
        //call add reason list controller
        return redirect()->action('ReasonListController@addReasonList', ['id' => $feedback_id]);
    }
}
