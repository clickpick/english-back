<?php

/** @noinspection NullPointerExceptionInspection */

namespace App\Http\Controllers\VkUser;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\LearnedResource;
use App\Lesson;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function learned(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $dayOffset = (int)$request->input('day_offset', 0);
        $dayLimit = (int)$request->input('day_limit', 10);

        $fromDate = Carbon::now()->subDays($dayOffset);
        $toDate = $fromDate->copy()->subDays($dayLimit);

        $learnedLessons = $user->lessons()
            ->where('is_learned', true)
            ->whereDate('send_at', '<=', $fromDate)
            ->whereDate('send_at', '>', $toDate)
            ->orderBy('send_at', 'desc')
            ->get();

        $firstLesson = $user->lessons()->orderBy('send_at', 'asc')->first();

        if ($firstLesson) {
            $firstLessonAt = $firstLesson->send_at->setHours(12)->setMinutes(0)->setSeconds(0);
        } else {
            $firstLessonAt = Carbon::now()->setHours(12)->setMinutes(0)->setSeconds(0);
        }

        $groupedLessons = $learnedLessons->groupBy(function (Lesson $lesson) {
            return $lesson->send_at->toDateString();
        });

        $phrases = $groupedLessons->mapWithKeys(function (Collection $lessons, $dateString) use ($firstLessonAt) {

            $lessonDate = Carbon::make($dateString)->setHours(12)->setMinutes(0)->setSeconds(0);

            $dayNum = (int)Carbon::make($lessonDate)->diffInDays($firstLessonAt, true) + 1;
            $phrases = $lessons->sortBy('send_at')->map(function (Lesson $lesson) {
                return $lesson->phrase;
            });


            return [
                $dayNum => [
                    'day_num' => $dayNum,
                    'phrases' => $phrases
                ]
            ];
        });

        return LearnedResource::collection($phrases);
    }
}
