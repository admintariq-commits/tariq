<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    /**
     * Log an action
     */
    public function log($action, $modelType, $modelId, $oldValues = [], $newValues = [], $description = null)
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('user-agent'),
            'description' => $description,
            'timestamp' => now(),
        ]);
    }

    /**
     * Log model changes
     */
    public function logModelChange($model, $action, $originalValues = [], $changedValues = [])
    {
        $this->log(
            $action,
            class_basename($model),
            $model->id,
            $originalValues,
            $changedValues,
            "{$action} " . class_basename($model) . " ID: {$model->id}"
        );
    }

    /**
     * Get audit history for model
     */
    public function getHistory($modelType, $modelId, $limit = 50)
    {
        return AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user actions
     */
    public function getUserActions($userId, $limit = 50)
    {
        return AuditLog::where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent actions
     */
    public function getRecentActions($limit = 20)
    {
        return AuditLog::orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Export audit log
     */
    public function exportLog($filters = [])
    {
        $query = AuditLog::query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }

        if (isset($filters['from_date'])) {
            $query->where('timestamp', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('timestamp', '<=', $filters['to_date']);
        }

        return $query->orderBy('timestamp', 'desc')->get();
    }
}
