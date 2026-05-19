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

    /**
     * Display the user profile with reservation history.
     */
    public function profile()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Get reservations matching the user's email or tenant ID
        $reservations = \App\Models\Reservation::where(function($query) use ($user) {
            $query->where('email', $user->email);
            if ($user->tenant) {
                $query->orWhere('tenant_id', $user->tenant->id);
            }
        })
        ->with('field')
        ->orderBy('request_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->get();

        return view('profile', compact('user', 'reservations'));
    }

    /**
     * Cancel a user's reservation.
     */
    public function cancelReservation(Request $request, $id)
    {
        $user = auth()->user();
        $reservation = \App\Models\Reservation::findOrFail($id);

        // Security check: must belong to user's tenant OR match user's email
        $belongsToUser = ($user->tenant && $reservation->tenant_id === $user->tenant->id) || ($reservation->email === $user->email);

        if (!$belongsToUser) {
            abort(403, 'Action non autorisée.');
        }

        // Check if the reservation is in a state that can be cancelled
        if (in_array($reservation->status, ['PENDING', 'APPROVED'])) {
            $reservation->cancel();
            return back()->with('success', 'Votre réservation a été annulée avec succès.');
        }

        return back()->with('error', 'Cette réservation ne peut plus être annulée.');
    }
}
