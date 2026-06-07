# ProMatch Chatbot

This document explains how the quick reservation chatbot works in the ProMatch Laravel project.

## Goal

The chatbot helps a signed-in client create a quick terrain reservation from the chat widget. It follows the same reservation logic as the normal booking form and sends the request to the admin dashboard as a `PENDING` reservation.

## User Workflow

1. The user opens the floating chatbot.
2. The chatbot asks: `Quel terrain voulez-vous reserver ?`
3. The user chooses a terrain by name or by number.
4. The chatbot asks for the reservation date.
5. The user sends a date like `2026-06-08`, `08/06/2026`, `aujourd hui`, or `demain`.
6. The chatbot checks available hours for that terrain and date.
7. The chatbot replies with available hours, for example: `08:00, 10:00, 14:00, 16:00, 18:00, 20:00`.
8. The user chooses an hour like `10h` or `10:00`.
9. The chatbot creates a reservation request with status `PENDING`.
10. The request appears in the admin reservation dashboard.

If the user is not signed in, the chatbot does not create a reservation. It asks the user to log in first.

## Important Files

- `routes/web.php`
  - Defines the chatbot route: `POST /chatbot/message`.

- `app/Http/Controllers/Public/ChatbotController.php`
  - Handles the guided chat workflow.
  - Checks authentication.
  - Detects terrain, date, and hour from user messages.
  - Reads available hours from `time_slots`.
  - Uses fallback hours when no real slots exist, matching the manual booking form.
  - Creates the reservation through `ReservationService`.

- `app/Services/ReservationService.php`
  - Creates the final `reservations` table record.
  - Resolves or creates the client tenant.
  - Sets reservation status to `PENDING`.

- `resources/views/components/chatbot.blade.php`
  - Contains the floating chatbot UI.
  - Shows the initial bot message.

- `resources/js/chatbot.js`
  - Opens and closes the chat panel.
  - Sends user messages to Laravel.
  - Keeps the current reservation state in the browser.
  - Displays bot replies.

- `resources/views/layouts/app.blade.php`
  - Includes the chatbot on public pages using the shared layout.

- `resources/views/booking.blade.php`
  - Includes the chatbot on the standalone booking page.

## Reservation State

The frontend sends a small state object with each message:

```json
{
  "field_id": 1,
  "date": "2026-06-08",
  "time": "10:00"
}
```

The server returns an updated state after each step. When the reservation is created successfully, the state is reset to an empty object.

## Availability Logic

The chatbot first checks real available slots:

- Same `field_id`
- Same `date`
- `status = AVAILABLE`
- No active `PENDING` or `APPROVED` reservation for that slot

If no real `time_slots` exist for that date, the chatbot uses the same fallback hours as the manual booking page:

```text
08:00, 10:00, 14:00, 16:00, 18:00, 20:00
```

Reservations created from fallback hours have `time_slot_id = null`, but still store `field_id`, `start_time`, `request_date`, client info, price, and `PENDING` status.

## Success Message

After creating the reservation, the chatbot replies:

```text
Demande envoyee pour Atlas - Terrain 3, le 2026-06-08 a 10:00. Elle est en attente de validation.
```

## Notes

- The chatbot quick reservation requires a signed-in user.
- The chatbot does not upload CNI files. It creates a quick request using the connected user's account information.
- The admin still validates or cancels the request from the dashboard.
