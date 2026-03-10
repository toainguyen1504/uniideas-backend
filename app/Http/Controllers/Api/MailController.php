<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function sendMail(Request $request)
    {
        Mail::to($request->email)
            ->send(new SendMail($request->only(['email', 'content'])));
            $request->session()->flash('message', 'Send mail was successfully!');
    
        try {
            Mail::mailer('mailtrap')
                ->to($request->email)
                ->send(new SendMail($request->only(['email', 'content'])));
            
            $request->session()->flash('message', 'Send mail was successfully via mailtrap!');
        } catch (\Exception $e) {
            Log::error('Lỗi kết nối Mailtrap: ' . $e->getMessage());
        }
        
        return view('mails.index');
    }
}
