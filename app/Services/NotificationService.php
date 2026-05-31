<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Graduate;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send job match notification
     */
    public function sendJobMatchNotification(Graduate $graduate, $jobData)
    {
        return Notification::create([
            'user_id' => $graduate->user_id,
            'type' => 'job_match',
            'title' => '🎯 Job Match Found!',
            'message' => "A job position in {$jobData['job_title']} has matched your profile.",
            'data' => $jobData,
            'action_url' => route('graduate.job-matches'),
            'icon' => '💼',
            'priority' => 'high',
        ]);
    }

    /**
     * Send unemployment alert
     */
    public function sendUnemploymentAlert(Graduate $graduate)
    {
        $monthsUnemployed = $graduate->months_unemployed;

        if ($monthsUnemployed > 6) {
            return Notification::create([
                'user_id' => $graduate->user_id,
                'type' => 'unemployment_alert',
                'title' => '⚠️ Long-term Unemployment Alert',
                'message' => "You have been unemployed for {$monthsUnemployed} months. Our team is here to help!",
                'data' => ['months_unemployed' => $monthsUnemployed],
                'action_url' => route('graduate.profile'),
                'icon' => '🆘',
                'priority' => 'critical',
            ]);
        }

        return null;
    }

    /**
     * Send verification complete notification
     */
    public function sendVerificationCompleteNotification($user, $recordType = 'academic')
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_complete',
            'title' => '✅ Verification Complete',
            'message' => "Your {$recordType} records have been verified successfully!",
            'data' => ['record_type' => $recordType],
            'action_url' => route('graduate.profile'),
            'icon' => '✔️',
            'priority' => 'medium',
        ]);
    }

    /**
     * Send verification needed notification
     */
    public function sendVerificationNeededNotification($user, $reason = 'Your academic records need verification')
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_needed',
            'title' => '📋 Verification Required',
            'message' => $reason,
            'data' => ['reason' => $reason],
            'action_url' => route('graduate.profile'),
            'icon' => '📝',
            'priority' => 'high',
        ]);
    }

    /**
     * Send system notification to admin
     */
    public function sendAdminNotification($userId, $title, $message, $data = [], $priority = 'medium')
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'icon' => '🔔',
            'priority' => $priority,
        ]);
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications($userId, $limit = 20)
    {
        return Notification::where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread count
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->count();
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Bulk send notifications
     */
    public function bulkSendNotification($userIds, $title, $message, $type = 'system', $priority = 'medium')
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return Notification::insert($notifications);
    }
}
