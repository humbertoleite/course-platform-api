<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Http\Requests\LessonStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a specific course.
     */
    public function index(Course $course): JsonResponse
    {
        $lessons = $course->lessons()->orderBy('order')->get();

        return response()->json($lessons);
    }

    /**
     * Store a newly created lesson for a specific course.
     */
    public function store(LessonStoreRequest $request, Course $course): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if (isset($validatedData['video_base64'])) {
                $videoBase64 = $validatedData['video_base64'];

                if (strpos($videoBase64, 'data:video/mp4;base64,') === 0) {
                    $videoBase64 = substr($videoBase64, 22);
                }

                $videoData = base64_decode($videoBase64);

                if ($videoData === false) {
                    return response()->json([
                        'message' => 'Invalid base64 video data',
                        'error' => 'The provided video data is not valid base64'
                    ], 400);
                }

                $filename = 'lesson_' . $course->id . '_' . time() . '_' . Str::random(10) . '.mp4';
                $filePath = 'lessons/' . $filename;

                // Try to store the file
                if (!Storage::disk('public')->put($filePath, $videoData)) {
                    return response()->json([
                        'message' => 'Failed to store video file',
                        'error' => 'Could not save the video file to storage'
                    ], 500);
                }

                $validatedData['video_url'] = Storage::url($filePath);
                unset($validatedData['video_base64']);
            }

            $lesson = $course->lessons()->create($validatedData);

            return response()->json([
                'message' => 'Lesson created successfully',
                'data' => $lesson
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the lesson',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified lesson.
     */
    public function show(Course $course, Lesson $lesson): JsonResponse
    {

        return response()->json($lesson);
    }

    /**
     * Update the specified lesson.
     */
    public function update(LessonStoreRequest $request, Course $course, Lesson $lesson): JsonResponse
    {
        $lesson->update($request->validated());

        return response()->json($lesson);
    }

    /**
     * Remove the specified lesson.
     */
    public function destroy(Course $course, Lesson $lesson): JsonResponse
    {
        $lesson->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
