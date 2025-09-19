<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GoogleDrivePublicVideoController extends Controller
{
    /**
     * Streams a video file from Google Drive using a service account.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fileId The Google Drive file ID.
     * @return StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function streamVideo(Request $request, string $fileId)
    {
        // Path to the service account key file from the .env configuration.
        $keyFilePath = Storage::disk('private')->path('google-service-account.json');

        // Check if the service account key file exists.
        if (!file_exists($keyFilePath)) {
            return response()->json(['error' => 'Service account key file not found.'], 500);
        }

        // Initialize the Google Client with the service account credentials.
        try {
            $client = new GoogleClient();
            $client->setAuthConfig($keyFilePath);
            $client->setScopes([GoogleDrive::DRIVE_READONLY]);
            $accessToken = $client->fetchAccessTokenWithAssertion();
            $token = $accessToken['access_token'];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to initialize Google Client: ' . $e->getMessage()], 500);
        }

        // Construct the Google Drive API URL for streaming the file content.
        $url = "https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media";

        // Get the range header from the request for seeking functionality.
        $range = $request->header('Range');

        try {
            // Get the file size from the video headers.
            $headResponse = Http::withToken($token)->head($url);
            if ($headResponse->failed() || !$headResponse->header('Content-Length')) {
                return response()->json(['error' => 'Failed to retrieve file metadata from Google Drive.'], $headResponse->status());
            }
            $fileSize = (int)$headResponse->header('Content-Length');
            $mimeType = $headResponse->header('Content-Type');

            // Set the default range for the entire file.
            $start = 0;
            $end = $fileSize - 1;
            $length = $fileSize;
            $status = 200;

            // Handle range requests for video seeking.
            if ($range) {
                $status = 206;
                preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
                $start = isset($matches[1]) ? intval($matches[1]) : 0;
                $end = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $fileSize - 1;

                if ($start > $end || $start >= $fileSize || $end >= $fileSize) {
                    return response()->json(['error' => 'Invalid range request.'], 416)->header('Content-Range', 'bytes */' . $fileSize);
                }
                $length = $end - $start + 1;
            }

            // Get the video stream from Google Drive with the correct range header.
            $response = Http::withToken($token)
                ->withHeaders(['Range' => "bytes={$start}-{$end}"])
                ->get($url);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to retrieve file from Google Drive.'], $response->status());
            }

            $stream = $response->toPsrResponse()->getBody();

            $headers = [
                'Content-Type' => $mimeType,
                'Accept-Ranges' => 'bytes',
                'Content-Length' => $length,
                'Content-Range' => "bytes $start-$end/$fileSize",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];

            return response()->stream(function () use ($stream) {
                while (!$stream->eof()) {
                    echo $stream->read(1024 * 1024);
                    flush();
                }
            }, $status, $headers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retrieves and streams the thumbnail image for a Google Drive video file.
     *
     * @param string $fileId The Google Drive file ID.
     * @return \Illuminate\Http\Response
     */
    public function getPreviewImage(string $fileId)
    {
        $keyFilePath = Storage::disk('private')->path('google-service-account.json');

        if (!file_exists($keyFilePath)) {
            return response()->json(['error' => 'Service account key file not found.'], 500);
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($keyFilePath);
            $client->setScopes([GoogleDrive::DRIVE_READONLY]);
            $accessToken = $client->fetchAccessTokenWithAssertion();
            $token = $accessToken['access_token'];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to authenticate service account: ' . $e->getMessage()], 500);
        }

        $url = "https://www.googleapis.com/drive/v3/files/{$fileId}?fields=thumbnailLink&alt=json";

        try {
            $response = Http::withToken($token)->get($url);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to get thumbnail link from Google Drive.'], $response->status());
            }

            $thumbnailLink = $response->json()['thumbnailLink'] ?? null;

            if (!$thumbnailLink) {
                // Fallback to a placeholder image if no thumbnail is available.
                return redirect('https://placehold.co/640x360/000000/FFFFFF?text=No+Preview');
            }

            // Parse the URL to get its components.
            $urlParts = parse_url($thumbnailLink);
            $query = [];
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $query);
            }

            // Remove existing size parameters to avoid conflicts.
            unset($query['s']);
            unset($query['sz']);

            // Set the desired size. Google will return the largest available size up to this value.
            $query['s'] = 2048;

            // Rebuild the URL with the new query string.
            $hdThumbnailLink = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . http_build_query($query);

            // Get the image content directly from the thumbnail link.
            $imageResponse = Http::get($hdThumbnailLink);

            if ($imageResponse->failed()) {
                return response()->json(['error' => 'Failed to retrieve thumbnail image.'], $imageResponse->status());
            }

            return response($imageResponse->body())
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}
