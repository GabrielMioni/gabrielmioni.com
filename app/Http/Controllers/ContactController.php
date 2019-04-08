<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendContactForm;

class ContactController extends Controller
{

    public function sendContactFormEmail(Request $request) {
        $contactData = json_decode($request->get('contactData'), true);

        $contactCompany = $contactData['company'];
        $contactEmail   = $contactData['email'];
        $contactMessage = $contactData['message'];
        $contactName    = $contactData['name'];
        $covfefe        = $contactData['covfefe'];

        if (strlen($covfefe) > 0) {
            return response()->json([
                'success' => 'Excellent success. Very good. The best.'
            ], 200);
        }

        Mail::to(getenv('MAIL_TO'))->queue(new SendContactForm($contactCompany, $contactEmail, $contactMessage, $contactName));
    }
}
