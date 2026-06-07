<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'in:reservation,event,partnership,other'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prenom est obligatoire.',
            'first_name.min' => 'Le prenom doit contenir au moins 2 caracteres.',
            'first_name.regex' => 'Le prenom ne doit contenir que des lettres.',
            'last_name.required' => 'Le nom est obligatoire.',
            'last_name.min' => 'Le nom doit contenir au moins 2 caracteres.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres.',
            'email.required' => 'L email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'subject.required' => 'Veuillez choisir un sujet.',
            'subject.in' => 'Le sujet selectionne est invalide.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caracteres.',
            'message.max' => 'Le message ne doit pas depasser 2000 caracteres.',
        ];
    }
}
