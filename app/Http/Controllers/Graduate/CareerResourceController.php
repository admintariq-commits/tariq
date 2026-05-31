<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Services\CareerResourceService;
use App\Models\CareerResource;
use App\Models\Graduate;
use Illuminate\Http\Request;

class CareerResourceController extends Controller
{
    protected $resourceService;

    public function __construct(CareerResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    /**
     * Career resources page
     */
    public function index()
    {
        $featured = $this->resourceService->getFeaturedResources(8);
        $categories = [
            'interview_prep' => '🎤 Interview Preparation',
            'skill_development' => '📚 Skill Development',
            'career_planning' => '🎯 Career Planning',
            'professional_development' => '💼 Professional Development',
        ];

        return view('graduate.career-resources', compact('featured', 'categories'));
    }

    /**
     * Get resources by category
     */
    public function category($category)
    {
        $resources = $this->resourceService->getResourcesByCategory($category, 20);
        $categoryName = $this->getCategoryName($category);

        return view('graduate.resources-category', compact('resources', 'category', 'categoryName'));
    }

    /**
     * Search resources
     */
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        $resources = $this->resourceService->searchResources($request->q);

        return view('graduate.resources-search', compact('resources', 'request'));
    }

    /**
     * View resource
     */
    public function show(CareerResource $resource)
    {
        $graduate = auth()->user()->graduate;
        
        // Track access
        $this->resourceService->trackAccess($graduate, $resource);
        $this->resourceService->incrementViewCount($resource);

        // Get similar resources
        $similar = CareerResource::where('type', $resource->type)
            ->where('id', '!=', $resource->id)
            ->limit(5)
            ->get();

        return view('graduate.resource-detail', compact('resource', 'similar'));
    }

    /**
     * Rate resource
     */
    public function rate(Request $request, CareerResource $resource)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $graduate = auth()->user()->graduate;
        $this->resourceService->rateResource(
            $graduate,
            $resource,
            $request->rating,
            $request->feedback
        );

        return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
    }

    /**
     * Get recommended resources for graduate
     */
    public function recommended()
    {
        $graduate = auth()->user()->graduate;
        $recommended = $this->resourceService->getRecommendedForGraduate($graduate, 10);

        return view('graduate.recommended-resources', compact('recommended'));
    }

    /**
     * Get category name
     */
    private function getCategoryName($category)
    {
        $names = [
            'interview_prep' => '🎤 Interview Preparation',
            'skill_development' => '📚 Skill Development',
            'career_planning' => '🎯 Career Planning',
            'professional_development' => '💼 Professional Development',
        ];

        return $names[$category] ?? $category;
    }
}
