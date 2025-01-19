<?php

namespace App\Http\Controllers\SocialMediaLink;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialMediaLink\SocialMediaLinkResource;
use App\Models\SocialMediaLink;
use Illuminate\Http\JsonResponse;

class SocialMediaLinkController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SocialMediaLinkResource::collection(SocialMediaLink::all()));
    }

    public function show($slug): JsonResponse
    {
        if (!SocialMediaLink::where('slug', $slug)->exists()) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(SocialMediaLinkResource::make(SocialMediaLink::where('slug', $slug)->first()));
    }
}
