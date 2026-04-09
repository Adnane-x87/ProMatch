<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\PublicFieldService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $fieldService;

    public function __construct(PublicFieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    /**
     * Display the landing page.
     */
    public function home()
    {
        $fields = $this->fieldService->searchFields();
        return view('welcome', compact('fields'));
    }

    /**
     * Display the booking page.
     */
    public function booking()
    {
        $fields = $this->fieldService->searchFields();
        return view('booking', compact('fields'));
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
