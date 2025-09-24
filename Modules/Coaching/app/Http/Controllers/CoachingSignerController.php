<?php

namespace Modules\Coaching\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingSigner;

class CoachingSignerController extends Controller
{
    public function list(Request $request)
    {
        $searchKeyword = $request->query('q');

        $coachingQuery = User::whereNotNull('nik')->select('id', 'name', 'jabatan')->limit(10);

        if ($searchKeyword) {
            $coachingQuery->where('name', 'like', '%' . $searchKeyword . '%');
        }

        $coachingSigners = $coachingQuery->get();

        return response()->json($coachingSigners);
    }

    // store coaching signer
    // this function receive user id and coaching id
    public function storeSigners(Request $request)
    {
        $validated = $request->validate([
            'front_tte' => 'required|exists:users,id',
            'back_tte' => 'required|exists:users,id',
            'coaching_id' => 'required|exists:coachings,id',
        ]);

        // front tte
        $userFrontTte = User::findOrFail($validated['front_tte']);

        // back tte
        $userBackTte = User::findOrFail($validated['back_tte']);

        try {
            DB::beginTransaction();
            $coachingSignerFront = CoachingSigner::create([
                'user_id' => $userFrontTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 1,
            ]);

            $coachingSignerBack = CoachingSigner::create([
                'user_id' => $userBackTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 2,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan signer');
        }

        return redirect()->back()->with('success', 'Signer berhasil disimpan');
    }

    // store choosed certificate type
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'coaching_id' => 'required|exists:coachings,id',
            'certificate_builder_id' => 'required|exists:certificate_builders,id',
        ]);

        $coaching = Coaching::findOrFail($validated['coaching_id']);
        $certificateBuilder = CertificateBuilder::findOrFail($validated['certificate_builder_id']);

        $coaching->certificate_id = $certificateBuilder->id;
        $coaching->save();

        return redirect()->back()->with('success', 'Tipe sertifikat berhasil disimpan');
    }
}
