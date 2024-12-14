<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GatewayController extends Controller
{
    protected $services = [
        'project' => 'http://127.0.0.1:8002/api', // رابط الخدمة الأولى
        'hrmngmnt' => 'http://127.0.0.1:8001/hrmngmnt', // رابط الخدمة الثانية
        'inventory' => 'http://127.0.0.1:8003/inventory', // رابط الخدمة الثانية
    ];

    public function handleRequest(Request $request, $service, $endpoint, $extra = null)
    {
        // إعداد روابط الخدمات

        if (!array_key_exists($service, $this->services)) {
            return response()->json(['error' => 'Invalid service'], 404);
        }

        // Construct the full URL
        $baseUrl = $this->services[$service];
        $fullEndpoint = $endpoint . ($extra ? '/' . $extra : '');
        $url = "{$baseUrl}/{$fullEndpoint}";
        // Forward the request
        $method = $request->method();
        $response = Http::withHeaders($request->headers->all())
            ->send($method, $url, [
                'query' => $request->query(),
                'json' => $request->all(),
            ]);

        Log::info($response);
        Log::info("Response status: " . $response->status());
        Log::info("Response body: " . $response->body());
        // Return the response
        return response()->json($response->json(), $response->status());
    }

    public function getChartData(Request $request)
    {
        // Define the URL for the "makeReport" API
        $url = "{$this->services['inventory']}/report"; // Ensure the route name is correctly defined in your web.php or api.php

        // Forward the request to the "makeReport" API
        $response = Http::withHeaders($request->headers->all())
            ->post($url, $request->all()); // Send request body as JSON

        // Check if the response is successful
        if ($response->successful()) {
            // Parse and return the data
            return $response;
        }

        // Handle errors
        return response()->json([
            'message' => 'Failed to fetch chart data',
            'details' => $response->body(),
        ], $response->status());
    }
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // Validate file type and size
        ]);

        $fileUrls = [];

        // Loop through the files and save them
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $fileUrls[] = Storage::url($path);
            }
        }
        return response()->json(['file_urls' => $fileUrls], 200);
    }
}
