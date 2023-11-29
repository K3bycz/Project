<?php

namespace App\Helpers;

use App\Models\TaskModel;
use App\Models\AchievementsToUserModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AchievementsHelper
{
    public static function checkAchievementProgress()
    {
        $countDone = TaskModel::where('user_id', Auth::id())
            ->where('status', 'Zakończone')
            ->count();

        $countInProgress = TaskModel::where('user_id', Auth::id())
            ->where('status', 'W trakcie')
            ->count();

        $countToDo = TaskModel::where('user_id', Auth::id())
            ->where('status', 'Nowe')
            ->count();
            
        $countWork = TaskModel::where('user_id', Auth::id())
            ->where('status', 'Zakończone')
            ->where('category', 'Praca')
            ->count();
        
        $countStudy = TaskModel::where('user_id', Auth::id())
            ->where('status', 'Zakończone')
            ->where('category', 'Studia')
            ->count();

        self::awardAchievement(1, $countDone, 1); // Pierwsze kroki
        self::awardAchievement(2, $countDone, 10); // Dziesięć na dziesięć
        self::awardAchievement(3, $countDone, 100); // Weteran
        self::awardAchievement(4, $countInProgress, 10); // Człowiek-orkiestra
        self::awardAchievement(5, $countToDo, 20); // Ręce pełne roboty
        self::awardAchievement(6, $countWork, 10); // Praca, praca
        self::awardAchievement(7, $countStudy , 10); // Mól książkowy
    }

    private static function awardAchievement($achievementId, $count, $target)
    {
        if ($count >= $target) {
            $existingRecord = AchievementsToUserModel::where('achievement_id', $achievementId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$existingRecord) {
                AchievementsToUserModel::create([
                    'achievement_id' => $achievementId,
                    'user_id' => Auth::id(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}