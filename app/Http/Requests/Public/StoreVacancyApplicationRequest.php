<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File as FileRule;
use Illuminate\Validation\Validator;

class StoreVacancyApplicationRequest extends FormRequest
{
    protected function getRedirectUrl(): string
    {
        $slug = (string) $this->route('slug');

        return route('vacancies.apply', ['slug' => $slug]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isApplicantAuthenticated = auth('applicant')->check();

        return [
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z]+\\s[A-Za-z]+\\s[A-Za-z]+$/'],
            'date_of_birth' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'in:male,female'],
            'nationality' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z ]+$/'],
            'disability_status' => ['required', 'boolean'],
            'disability_type' => ['nullable', 'string', 'max:255', 'required_if:disability_status,1'],
            'education_level' => ['required', 'string', 'max:255'],
            'field_of_study' => ['required', 'string', 'max:255'],
            'university_name' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'integer', 'between:2015,2018'],
            'gpa' => ['required', 'numeric', 'between:3,4', 'regex:/^\\d\\.\\d{2}$/'],
            'education_document' => [$isApplicantAuthenticated ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:2048'],
            'profile_photo' => [$isApplicantAuthenticated ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\\d{10}$/'],
            'national_id_number' => ['required', 'regex:/^\\d{16}$/'],
            'email' => [$isApplicantAuthenticated ? 'nullable' : 'required', 'email', 'max:255'],
            'password' => [
                $isApplicantAuthenticated ? 'nullable' : 'required',
                $isApplicantAuthenticated ? 'nullable' : 'confirmed',
                $isApplicantAuthenticated ? 'nullable' : Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $fullName = trim(preg_replace('/\\s+/', ' ', strip_tags((string) $this->input('full_name'))));
        $nationality = trim(preg_replace('/\\s+/', ' ', strip_tags((string) $this->input('nationality'))));
        $nationalIdRaw = trim(strip_tags((string) $this->input('national_id_number')));
        $phoneRaw = trim(strip_tags((string) $this->input('phone')));
        $dobRaw = trim(strip_tags((string) $this->input('date_of_birth')));

        $normalizedDob = $dobRaw;
        if (preg_match('/^\\d{2}\\/\\d{2}\\/\\d{4}$/', $dobRaw)) {
            $parts = explode('/', $dobRaw);
            if (count($parts) === 3) {
                $date = \DateTime::createFromFormat('d/m/Y', $dobRaw);
                if ($date && $date->format('d/m/Y') === $dobRaw) {
                    $normalizedDob = $date->format('Y-m-d');
                }
            }
        }

        $this->merge([
            'full_name' => $fullName,
            'nationality' => $nationality,
            'phone' => preg_replace('/\\s+/', '', $phoneRaw),
            'email' => trim(strip_tags((string) $this->input('email'))),
            'address' => trim(strip_tags((string) $this->input('address'))),
            'national_id_number' => preg_replace('/\\s+/', '', $nationalIdRaw),
            'disability_type' => trim(strip_tags((string) $this->input('disability_type'))),
            'education_level' => trim(strip_tags((string) $this->input('education_level'))),
            'field_of_study' => trim(strip_tags((string) $this->input('field_of_study'))),
            'university_name' => trim(strip_tags((string) $this->input('university_name'))),
            'cover_letter' => trim(strip_tags((string) $this->input('cover_letter'))),
            'date_of_birth' => $normalizedDob,
            'national_id_number_raw' => $nationalIdRaw,
            'phone_raw' => $phoneRaw,
            'date_of_birth_raw' => $dobRaw,
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $fullName = (string) $this->input('full_name');
            if (! preg_match('/^[A-Za-z]+\\s[A-Za-z]+\\s[A-Za-z]+$/', $fullName)) {
                $validator->errors()->add('full_name', 'Full name must contain exactly three alphabetic words.');
            }

            $nationality = (string) $this->input('nationality');
            if (! preg_match('/^[A-Za-z ]+$/', $nationality)) {
                $validator->errors()->add('nationality', 'Nationality must contain only letters and spaces.');
            }

            $nationalIdRaw = (string) $this->input('national_id_number_raw');
            if ($nationalIdRaw !== '' && preg_match('/[^0-9\\s]/', $nationalIdRaw)) {
                $validator->errors()->add('national_id_number', 'National ID number must contain digits only.');
            }

            $phoneRaw = (string) $this->input('phone_raw');
            if ($phoneRaw !== '' && preg_match('/\\D/', $phoneRaw)) {
                $validator->errors()->add('phone', 'Phone number must contain digits only.');
            }

            $dobRaw = (string) $this->input('date_of_birth_raw');
            if ($dobRaw === '' || ! preg_match('/^\\d{2}\\/\\d{2}\\/\\d{4}$/', $dobRaw)) {
                $validator->errors()->add('date_of_birth', 'Date of birth must be in dd/mm/yyyy format.');
            } else {
                $date = \DateTime::createFromFormat('d/m/Y', $dobRaw);
                if (! $date || $date->format('d/m/Y') !== $dobRaw) {
                    $validator->errors()->add('date_of_birth', 'Date of birth must be a valid date.');
                }
            }

            $educationDocument = $this->file('education_document');
            if ($educationDocument) {
                $ext = strtolower($educationDocument->getClientOriginalExtension());
                $mime = $educationDocument->getMimeType();
                $name = strtolower($educationDocument->getClientOriginalName());
                if ($ext !== 'pdf' || $mime !== 'application/pdf') {
                    $validator->errors()->add('education_document', __('vacancies.public.education_document_hint'));
                }
                if (preg_match('/\.(pdf)\.[a-z0-9]+$/i', $name) || preg_match('/\.(exe|php|js|sh|bat|cmd|com|jar|vbs|msi)$/i', $name)) {
                    $validator->errors()->add('education_document', __('vacancies.public.education_document_hint'));
                }
            }

            $profilePhoto = $this->file('profile_photo');
            if ($profilePhoto) {
                $ext = strtolower($profilePhoto->getClientOriginalExtension());
                $mime = $profilePhoto->getMimeType();
                $name = strtolower($profilePhoto->getClientOriginalName());
                $validExt = in_array($ext, ['jpg', 'jpeg', 'png'], true);
                $validMime = in_array($mime, ['image/jpeg', 'image/png'], true);
                if (! $validExt || ! $validMime) {
                    $validator->errors()->add('profile_photo', __('vacancies.public.profile_photo_hint'));
                }
                if (preg_match('/\.(jpg|jpeg|png)\.[a-z0-9]+$/i', $name) || preg_match('/\.(exe|php|js|sh|bat|cmd|com|jar|vbs|msi)$/i', $name)) {
                    $validator->errors()->add('profile_photo', __('vacancies.public.profile_photo_hint'));
                }
            }
        });
    }
}
