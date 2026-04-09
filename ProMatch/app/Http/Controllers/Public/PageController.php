<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the landing page.
     */
    public function home()
    {
        return view('welcome');
    }

    /**
     * Display the booking page.
     */
    public function booking()
    {
        return view('booking');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        // Simple placeholder for contact form submission
        return back()->with('success', 'Votre message a été envoyé avec succès !');
    }
}
