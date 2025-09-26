<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CourseRatingService
{
    /**
     * Calculates and returns the average rating for a specific Course.
     */
    public function getAverageRating(Course $course): float
    {
        return (float) $course->comments()->avg('rating') ?? 0.0;
    }

    /**
     * Calculates and attaches the average rating for a collection of courses in one query.
     */
    public function attachAverageRating(Collection $courses): Collection
    {
        if ($courses->isEmpty()) {
            return $courses;
        }

        $ratings = DB::table('comments')
            ->select('course_id', DB::raw('AVG(rating) as avg_rating'))
            ->whereIn('course_id', $courses->pluck('id'))
            ->groupBy('course_id')
            ->get()
            ->keyBy('course_id');

        return $courses->map(function ($course) use ($ratings) {
            $course->average_rating = (float) ($ratings->get($course->id)?->avg_rating ?? 0.0);
            return $course;
        });
    }
}
