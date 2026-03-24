<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServicePackage;
use Illuminate\Http\JsonResponse;

class ProductLookupController extends Controller
{
    /**
     * Get packages for a specific service.
     */
    public function getPackages(Service $service): JsonResponse
    {
        $packages = ServicePackage::where('service_id', $service->id)
            ->select('id', 'name', 'price')
            ->get();

        return response()->json($packages);
    }
}
