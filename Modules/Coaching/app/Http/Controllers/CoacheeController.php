<?php

namespace Modules\Coaching\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Coaching\app\Models\CoachingSessionDetail;
use Modules\Coaching\app\Models\CoachingUser;

class CoacheeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = userAuth();
        $coachings = CoachingUser::with('coaching')->where('user_id', $user->id)->orderByDesc('id')->paginate(10);
        return view('frontend.student-dashboard.coaching.coachee.index', compact('coachings'));
    }

    public function show($id)
    {
        $user = userAuth();
        $coachingUser = CoachingUser::with(['coaching.coach', 'coaching.coachingSessions.details' => function ($q) {
            return $q->where('coaching_user_id', userAuth()->id);
        }])->where('user_id', $user->id)->where('coaching_id', $id)->first();
        // dd($coachingUser);
        if (!$coachingUser) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses Coaching ini.');
        }

        $coaching = $coachingUser->coaching;
        $sessions = $coaching->coachingSessions;

        return view('frontend.student-dashboard.coaching.coachee.show', compact('coachingUser', 'coaching', 'sessions'));
    }

    public function joinKonsensus($id)
    {
        $user = userAuth();
        $coachingUser = CoachingUser::with('coaching')->where('user_id', $user->id)->where('coaching_id', $id)->first();

        if (!$coachingUser) {
            return redirect()->route('student.coachee.show', ['id' => $id])->with(['alert-type' => 'error', 'messege' => __('Anda tidak memiliki izin untuk mengakses Coaching ini.')]);
        }

        if ($coachingUser->isRejected()) {
            return redirect()->route('student.coachee.show', ['id' => $id])->with(['alert-type' => 'error', 'messege' => __('Anda tidak bisa bergabung Coaching ini, karena anda sudah menolak Coaching ini.')]);
        }

        if ($coachingUser->is_joined == 1) {
            return redirect()->route('student.coachee.show', ['id' => $id])->with(['alert-type' => 'error', 'messege' => __('Anda sudah bergabung Coaching ini.')]);
        }

        $coachingUser->is_joined = 1;
        $coachingUser->joined_at = now();
        $coachingUser->save();


        return redirect()->route('student.coachee.show', ['id' => $coachingUser->coaching->id])->with(['alert-type' => 'success', 'messege' => __('Anda telah bergabung dengan Coaching ini')]);
    }

    public function tolakKonsensus(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required',
        ], [
            'reason.required' => 'Alasan harus diisi.',
        ]);

        $user = userAuth();
        $coachingUser = CoachingUser::with('coaching')->where('user_id', $user->id)->where('coaching_id', $id)->first();

        if (!$coachingUser) {
            return response()->json([
                'status' => 'error',
                'message' => __('Anda tidak memiliki izin untuk mengakses Coaching ini.')
            ], 400);
        }

        if ($coachingUser->is_joined == 1) {
            return response()->json([
                'status' => 'error',
                'message' => __('Anda tidak bisa menolak Coaching ini, karena Anda sudah bergabung Coaching ini.')
            ], 400);
        }

        $coachingUser->is_joined = 0;
        $coachingUser->notes = $request->reason;
        $coachingUser->save();
        return response()->json([
            'status' => 'success',
            'message' => __('Anda telah menolak Coaching ini')
        ]);
    }

    public function submitReport(Request $request)
    {
        $request->validate([
            'session_id' => 'required',
            'activity' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'session_id.required' => 'Sesi harus dipilih.',
            'activity.required' => 'Kegiatan harus dipilih.',
            'image.required' => 'Gambar harus dipilih.',
        ]);

        $user = userAuth();
        $details = CoachingSessionDetail::with('session.coaching.coachingSessions')->where('coaching_session_id', $request->session_id)->where('coaching_user_id', $user->id)->first();

        if (!$details) {
            $season = CoachingSession::with('coaching.coachingSessions')->where('id', $request->session_id)->first();
            $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $season->coaching_id)->where('is_joined', 1)->first();

            if (!$coachingUser) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Anda tidak memiliki izin untuk mengakses Coaching ini.')]);
            }

            if ($coachingUser->is_joined == 0) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Anda belum bergabung Coaching ini.')]);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'coaching/' . $season->coaching_id . '/' . now()->year . '/' . $season->id . '/coaching_session' . $season->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $request->merge(['image' => $fileName]);
            }

            $result = CoachingSessionDetail::create([
                'coaching_session_id' => $season->id,
                'coaching_user_id' => $coachingUser->id,
                'activity' => "Pertemuan " . $season->coaching->coachingSessions->count(),
                'description' => $request->activity,
                'image' => $request->image,
            ]);

            if ($result) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'success', 'messege' => __('Laporan berhasil disimpan')]);
            }

            return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Laporan gagal disimpan')]);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'coaching/' . $details->session->coaching_id . '/' . now()->year . '/' . $details->session->id . '/coaching_session' . $details->session->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
            $request->merge(['image' => $fileName]);
        }

        $result = $details->update([
            'description' => $request->activity,
            'image' => $request->image,
        ]);

        if ($result) {
            return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'success', 'messege' => __('Laporan berhasil disimpan')]);
        }

        return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Laporan gagal disimpan')]);
    }
}
