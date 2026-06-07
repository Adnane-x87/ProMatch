<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $timeSlotId = $this->input('time_slot_id');
        $selectedTime = $this->input('selected_time');
        $date = $this->input('date');

        if ($timeSlotId !== null && (int) $timeSlotId >= 9000) {
            $timeSlotId = null;
        }

        if ($selectedTime && $date && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $selectedTime)) {
            $selectedTime = $date . ' ' . (strlen($selectedTime) === 5 ? $selectedTime . ':00' : $selectedTime);
        }

        $this->merge([
            'time_slot_id' => $timeSlotId,
            'selected_time' => $selectedTime,
        ]);
    }

    public function rules(): array
    {
        $hasValidatedCni = (bool) $this->user()?->tenant?->is_cni_valid;

        return [
            'terrain_id' => ['required', 'integer', 'exists:fields,id'],
            'time_slot_id' => ['nullable', 'integer', 'exists:time_slots,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'selected_time' => ['required', 'date'],
            'first_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\s().-]+$/'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'cni_image' => [$hasValidatedCni ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'terrain_id.required' => 'Veuillez choisir un terrain.',
            'terrain_id.exists' => 'Le terrain selectionne est introuvable.',
            'date.required' => 'Veuillez choisir une date.',
            'date.after_or_equal' => 'La date de reservation doit etre aujourd hui ou une date future.',
            'selected_time.required' => 'Veuillez selectionner une heure.',
            'selected_time.date' => 'L heure selectionnee est invalide.',
            'first_name.required' => 'Le prenom est obligatoire.',
            'first_name.min' => 'Le prenom doit contenir au moins 2 caracteres.',
            'first_name.regex' => 'Le prenom ne doit contenir que des lettres.',
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.min' => 'Le nom doit contenir au moins 2 caracteres.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres.',
            'email.required' => 'L email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'phone.required' => 'Le telephone est obligatoire.',
            'phone.regex' => 'Veuillez saisir un numero de telephone valide.',
            'cni_image.required' => 'Veuillez ajouter une image de votre CNI.',
            'cni_image.image' => 'La CNI doit etre une image.',
            'cni_image.mimes' => 'La CNI doit etre au format JPG ou PNG.',
            'cni_image.max' => 'La CNI ne doit pas depasser 4 Mo.',
        ];
    }
}
