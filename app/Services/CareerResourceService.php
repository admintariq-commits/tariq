<?php

namespace App\Services;

use App\Models\CareerResource;
use App\Models\CareerResourceAccess;
use App\Models\Graduate;

class CareerResourceService
{
    /**
     * Get featured resources
     */
    public function getFeaturedResources($limit = 10)
    {
        return CareerResource::where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get resources by category
     */
    public function getResourcesByCategory($category, $limit = 20)
    {
        return CareerResource::where('category', $category)
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search resources
     */
    public function searchResources($query, $limit = 20)
    {
        return CareerResource::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Track resource access
     */
    public function trackAccess(Graduate $graduate, CareerResource $resource)
    {
        return CareerResourceAccess::updateOrCreate(
            [
                'graduate_id' => $graduate->id,
                'resource_id' => $resource->id,
            ],
            [
                'accessed_at' => now(),
            ]
        );
    }

    /**
     * Get graduate's recommended resources
     */
    public function getRecommendedForGraduate(Graduate $graduate, $limit = 5)
    {
        $resources = CareerResource::where(function ($query) use ($graduate) {
            if ($graduate->employment_status === 'unemployed') {
                $query->orWhere('category', 'interview_prep')
                    ->orWhere('category', 'job_search');
            }

            if ($graduate->employment_status === 'employed') {
                $query->orWhere('category', 'professional_development')
                    ->orWhere('category', 'skill_development');
            }
        })
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();

        return $resources;
    }

    /**
     * Rate resource
     */
    public function rateResource(Graduate $graduate, CareerResource $resource, $rating, $feedback = null)
    {
        CareerResourceAccess::updateOrCreate(
            [
                'graduate_id' => $graduate->id,
                'resource_id' => $resource->id,
            ],
            [
                'rating' => $rating,
                'feedback' => $feedback,
                'completed' => true,
            ]
        );

        // Update resource rating
        $avgRating = CareerResourceAccess::where('resource_id', $resource->id)
            ->whereNotNull('rating')
            ->avg('rating');

        $helpfulCount = CareerResourceAccess::where('resource_id', $resource->id)
            ->where('rating', '>=', 4)
            ->count();

        $resource->update([
            'rating' => round($avgRating, 2),
            'helpful_count' => $helpfulCount,
        ]);
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(CareerResource $resource)
    {
        return $resource->increment('views_count');
    }
}
