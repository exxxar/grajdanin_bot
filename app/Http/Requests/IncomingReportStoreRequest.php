<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomingReportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $digits = preg_replace('/\D/', '', (string) $this->input('phone'));

            if (strlen($digits) === 11 && str_starts_with($digits, '7')) {
                $this->merge(['phone' => '+' . $digits]);
            } elseif (strlen($digits) === 10) {
                $this->merge(['phone' => '+7' . $digits]);
            }
        }

        foreach (['problems', 'solutions', 'difficulties', 'help_formats'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->merge([$field => $decoded]);
                }
            }
        }

        if (is_array($this->input('help_formats'))) {
            $this->merge([
                'help_formats' => array_values(array_filter(
                    $this->input('help_formats'),
                    fn ($id) => $id !== null && $id !== ''
                )),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'integer', Rule::in([0])],
            'received_from' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^\+7\d{10}$/'],
            'municipality_id' => ['required', 'integer', 'exists:municipalities,id'],
            'received_at' => ['required', 'date'],
            'to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'problem_description' => ['required', 'string', 'min:10', 'max:5000'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'help_formats' => ['nullable', 'array', 'max:10'],
            'help_formats.*' => ['integer', 'exists:help_formats,id'],
            'problems' => ['nullable', 'array'],
            'solutions' => ['nullable', 'array'],
            'difficulties' => ['nullable', 'array'],
            'documents' => ['nullable', 'array', 'max:10'],
            'documents.*' => ['file', 'max:20480'],
            'audio_files' => ['nullable', 'array', 'max:10'],
            'audio_files.*' => ['file', 'mimetypes:audio/webm,audio/mpeg,audio/wav,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
        ];
    }

    public function messages(): array
    {
        return [
            'received_from.required' => 'Укажите ФИО заявителя.',
            'phone.required' => 'Укажите номер телефона.',
            'phone.regex' => 'Телефон должен быть в формате +7XXXXXXXXXX.',
            'municipality_id.required' => 'Выберите муниципалитет.',
            'problem_description.required' => 'Опишите особенности проблемы.',
            'problem_description.min' => 'Описание проблемы должно быть не короче 10 символов.',
            'received_at.required' => 'Укажите дату получения обращения.',
            'documents.max' => 'Можно прикрепить не более 10 файлов.',
            'audio_files.max' => 'Можно прикрепить не более 10 аудиозаписей.',
        ];
    }
}
