<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonStoreRequest extends FormRequest
{
    public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'video_base64' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $videoBase64 = $this->input('video_base64');

            if ($videoBase64) {
                if (strpos($videoBase64, 'data:video/mp4;base64,') === 0) {
                    $videoBase64 = substr($videoBase64, 22);
                }

                if (!base64_decode($videoBase64, true)) {
                    $validator->errors()->add('video_base64', 'Invalid base64 video data.');
                }

                $decodedSize = strlen(base64_decode($videoBase64));
                if ($decodedSize > 100 * 1024 * 1024) {
                    $validator->errors()->add('video_base64', 'Video file is too large. Maximum size is 100MB.');
                }
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'message' => 'Unauthorized. You can only create lessons for your own courses.'
            ], 403)
        );
    }
}
