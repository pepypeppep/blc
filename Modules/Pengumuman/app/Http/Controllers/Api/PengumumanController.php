<?php

namespace Modules\Pengumuman\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\Pengumuman\app\Http\Requests\PostRequest;
use Modules\Pengumuman\app\Http\Requests\SubmissionRequest;
use Modules\Pengumuman\app\Models\Pengumuman;

class PengumumanController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $pengumumans = Pengumuman::with('pengumumanTags');

            if ($request->keyword) {
                $pengumumans = $pengumumans->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('content', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                });
            }

            if ($request->status) {
                $pengumumans = $pengumumans->where('status', $request->status);
            }

            if ($request->limit) {
                $pengumumans = $pengumumans->limit($request->limit);
            }

            $pengumumans = $pengumumans->get();
            return $this->successResponse($pengumumans, 'Pengumumans fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function store(PostRequest $request)
    {
        try {
            $validated = $request->validated();

            $course = Course::with(['instructor', 'instructor.unor'])->findOrFail($validated['course_id']);

            $instructor = $course->instructor;
            $unor = $course->instructor->unor;

            $validated = array_merge($validated, [
                'author_id' => $instructor->id,
                'verificator_id' =>  $instructor->id,
                'instansi' => $unor->name
            ]);

            $validated['verificator_id'] = $course->instructor_id;
            $validated['slug'] = generateUniqueSlug(Pengumuman::class, $validated['title']);
            $validated['status'] = "draft";
            $validated['published_at'] = now();
            $pengumuman = Pengumuman::create($validated);

            if (isset($validated['tags'])) {
                $tags = [];
                foreach ($validated['tags'] as $tag) {
                    $res = Tag::firstOrCreate(['name' => $tag]);
                    array_push($tags, $res->id);
                }
                $pengumuman->pengumumanTags()->attach($tags);
            }

            $pengumuman->save();

            return $this->successResponse($pengumuman, 'Pengumuman created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function update(PostRequest $request, $slug)
    {
        try {
            $validated = $request->validated();
            $pengumuman = Pengumuman::where('slug', $slug)->first();

            if (!$pengumuman) {
                return $this->errorResponse('Pengumuman not found', [], 404);
            }
            if ($pengumuman->status != 'draft') {
                return $this->errorResponse('Pengumuman is not in draft status', [], 400);
            }

            if (isset($validated['tags'])) {
                $tags = [];
                foreach ($validated['tags'] as $tag) {
                    $res = Tag::firstOrCreate(['name' => $tag]);
                    array_push($tags, $res->id);
                }
                $pengumuman->pengumumanTags()->sync($tags);
            }


            $pengumuman->update($validated);
            return $this->successResponse($pengumuman, 'Pengumuman updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function destroy($slug)
    {
        try {
            $pengumuman = Pengumuman::where('slug', $slug)->first();

            if (!$pengumuman) {
                return $this->errorResponse('Pengumuman not found', [], 404);
            }

            if ($pengumuman->status != 'draft') {
                return $this->errorResponse('Pengumuman is not in draft status', [], 400);
            }


            $pengumuman->delete();
            return $this->successResponse([], 'Pengumuman deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function show($slug)
    {
        try {
            $pengumuman = Pengumuman::where('slug', $slug)->first();
            if (!$pengumuman) {
                return $this->errorResponse('Pengumuman not found', [], 404);
            }
            return $this->successResponse($pengumuman, 'Pengumuman fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updateSubmission(SubmissionRequest $request, $slug)
    {
        try {
            $validated = $request->validated();
            $pengumuman = Pengumuman::where('slug', $slug)->first();

            if (!$pengumuman) {
                return $this->errorResponse('Pengumuman not found', [], 404);
            }

            if ($pengumuman->status != 'draft') {
                return $this->errorResponse('Pengumuman is not in draft status', [], 400);
            }

            $pengumuman->status = $validated['status'];
            $pengumuman->save();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
