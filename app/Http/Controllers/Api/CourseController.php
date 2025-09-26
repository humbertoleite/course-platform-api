<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Instructor;
use App\Services\CourseRatingService;
use App\Http\Requests\CourseStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{

    public function __construct(protected CourseRatingService $ratingService)
    {
        //
    }


    public function index(): JsonResponse
    {

        $courses = Course::with('instructor')
            ->paginate(15);



        $courses->getCollection()->transform(function ($course) {
            $course->average_rating = $this->ratingService->getAverageRating($course);
            return $course;
        });

        return response()->json($courses);
    }

    /**
     * Helper method to retrieve a minimum, cached list of all Instructors.
     */
    protected function getOptimizedInstructors()
    {
        return Cache::remember('all_instructors_list', 3600, function () {
            return Instructor::select('id', 'name','bio')
                ->orderBy('name')
                ->get();
        });
    }


    public function store(CourseStoreRequest $request): JsonResponse
    {
        $course = Course::create($request->validated());
        return response()->json($course, 201);
    }


    public function show(Course $course): JsonResponse
    {
        $course->load(['instructor', 'lessons', 'comments.user']);

        $course->average_rating = $this->ratingService->getAverageRating($course);

        return response()->json($course);
    }

    public function update(CourseStoreRequest $request, Course $course): JsonResponse
    {
        $course->update($request->validated());
        return response()->json($course);
    }

    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return response()->json(null, 204); // 204 No Content
    }

    // Expose the optimized list to a public route
    public function getInstructorsList(): JsonResponse
    {
        return response()->json($this->getOptimizedInstructors());
    }
}
