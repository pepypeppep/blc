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
        $coachings = CoachingUser::whereHas('coaching', function ($q) {
            $q->whereNot('status', Coaching::STATUS_DRAFT);
        })
            ->where('user_id', $user->id)
            ->latest('id')
            ->paginate(10);
        return view('frontend.student-dashboard.coaching.coachee.index', compact('coachings'));
    }

    public function show($id)
    {
        $user = userAuth();
        $coachingUser = CoachingUser::whereHas('coaching', function ($q) {
            $q->whereNot('status', Coaching::STATUS_DRAFT);
        })->with(['coaching.coach', 'coaching.coachingSessions.details'])->where('user_id', $user->id)->where('coaching_id', $id)->first();

        if (!$coachingUser) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses Coaching ini.');
        }

        $coaching = $coachingUser->coaching;
        $sessions = $coaching->coachingSessions;
        $sessionsCount = $sessions->count();
        $userCanSubmitFinalReport = CoachingSessionDetail::where('coaching_user_id', $coachingUser->id)->whereIn('coaching_session_id', $sessions->pluck('id'))->where('coaching_note', '!=', null)->where('coaching_instructions', '!=', null)->count() == $sessionsCount && $coachingUser->final_report == null;

        return view('frontend.student-dashboard.coaching.coachee.show', compact('coachingUser', 'coaching', 'sessions', 'sessionsCount', 'userCanSubmitFinalReport'));
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
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'session_id.required' => 'Sesi harus dipilih.',
            'activity.required' => 'Kegiatan harus dipilih.',
            'description.required' => 'Hambatan harus diisi.',
            'image.required' => 'Gambar harus dipilih.',
        ]);

        $user = userAuth();
        $details = CoachingSessionDetail::with('session.coaching.coachingSessions')->where('coaching_session_id', $request->session_id)->where('coaching_user_id', $user->id)->first();

        if (!$details) {
            $season = CoachingSession::with('coaching.coachingSessions')->where('id', $request->session_id)->first();

            if ($season->coaching->status != Coaching::STATUS_PROCESS) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Pengisian laporan hanya dapat dilakukan pada sesi Coaching yang sedang berlangsung.')]);
            }

            $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $season->coaching_id)->where('is_joined', 1)->first();

            if (!$coachingUser) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Anda tidak memiliki izin untuk mengakses Coaching ini.')]);
            }

            if ($coachingUser->is_joined == 0) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Anda belum bergabung Coaching ini.')]);
            }

            $seasons = $season->coaching->coachingSessions;
            $currentIndex = $seasons->search(function ($item) use ($season) {
                return $item->id === $season->id;
            });
            if ($currentIndex > 0 && $seasons[$currentIndex - 1]->details->count() == 0) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Anda belum mengirimkan laporan untuk sesi sebelumnya.')]);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'coaching/' . $season->coaching_id . '/' . now()->year . '/' . $season->id . '/coaching_session' . $season->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $request->merge(['fileName' => $fileName]);
            }

            $result = CoachingSessionDetail::create([
                'coaching_session_id' => $season->id,
                'coaching_user_id' => $coachingUser->id,
                'activity' => $request->activity,
                'description' => $request->description,
                'image' => $request->fileName,
            ]);

            if ($result) {
                return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'success', 'messege' => __('Laporan berhasil disimpan')]);
            }

            return redirect()->route('student.coachee.show', ['id' => $season->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Laporan gagal disimpan')]);
        }

        if ($details->session->coaching->status != Coaching::STATUS_PROCESS) {
            return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Pengisian laporan hanya dapat dilakukan pada sesi Coaching yang sedang berlangsung.')]);
        }

        if ($details->coaching_note || $details->coaching_instructions) {
            return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Laporan yang sudah ditinjau coach tidak dapat diubah.')]);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'coaching/' . $details->session->coaching_id . '/' . now()->year . '/' . $details->session->id . '/coaching_session' . $details->session->coaching->coachingSessions->count() . '.' . $file->getClientOriginalExtension();
            Storage::disk('private')->put($fileName, file_get_contents($file));
            $request->merge(['fileName' => $fileName]);
        }

        $result = $details->update([
            'description' => $request->activity,
            'image' => $request->fileName,
        ]);

        if ($result) {
            return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'success', 'messege' => __('Laporan berhasil disimpan')]);
        }

        return redirect()->route('student.coachee.show', ['id' => $details->session->coaching_id])->with(['alert-type' => 'error', 'messege' => __('Laporan gagal disimpan')]);
    }

    public function previewFileName($coachingId, $coachingSessionId)
    {
        $user = userAuth();
        $details = CoachingSessionDetail::with(['session.coaching.coachingSessions' => function ($q) use ($coachingId) {
            $q->where('coaching_id', $coachingId);
        }])
            ->where('coaching_session_id', $coachingSessionId)
            ->whereHas('coachingUser', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$details) {
            return response()->json(['error' => 'Laporan tidak ditemukan'], 404);
        }

        return response()->file(Storage::disk('private')->path($details->image));
    }

    public function previewFinalReport($coachingId, $coachingUserId)
    {
        $user = userAuth();
        $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $coachingId)->where('id', $coachingUserId)->first();

        if (!$coachingUser) {
            return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'error', 'messege' => __('Anda belum bergabung Coaching ini.')]);
        }

        return response()->file(Storage::disk('private')->path($coachingUser->final_report));
    }

    public function submitFinalReport(Request $request, $coachingId)
    {
        $request->validate([
            'final_report' => 'required|file|mimes:pdf|max:5120',
        ]);

        try {
            $user = userAuth();
            $coachingUser = CoachingUser::where('user_id', $user->id)->where('coaching_id', $coachingId)->first();

            if (!$coachingUser) {
                return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'error', 'messege' => __('Anda belum bergabung Coaching ini.')]);
            }

            $details = CoachingSessionDetail::with(['session.coaching.coachingSessions' => function ($q) use ($coachingId) {
                $q->where('coaching_id', $coachingId);
            }])->where('coaching_user_id', $coachingUser->id)->count();

            $sessionsCount = CoachingSession::where('coaching_id', $coachingId)->count();

            if ($details < $sessionsCount) {
                return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'error', 'messege' => __('Anda belum menyelesaikan semua sesi Coaching. Pastikan Anda telah mengirimkan laporan untuk setiap sesi sebelum mengirimkan laporan akhir.')]);
            }

            if ($request->hasFile('final_report')) {
                $file = $request->file('final_report');
                $fileName = 'coaching/'  . $coachingUser->coaching_id . '/' . now()->year . '/' . 'final_report' . '/coaching_user' . $coachingUser->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('private')->put($fileName, file_get_contents($file));
                $request->merge(['fileName' => $fileName]);
            }

            $result = $coachingUser->update([
                'final_report' => $request->fileName,
            ]);

            if ($result) {
                return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'success', 'messege' => __('Laporan berhasil disimpan')]);
            }

            return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'error', 'messege' => __('Laporan gagal disimpan')]);
        } catch (\Exception $e) {
            return redirect()->route('student.coachee.show', ['id' => $coachingId])->with(['alert-type' => 'error', 'messege' => __('Terjadi kesalahan: ') . $e->getMessage()]);
        }
    }
}
