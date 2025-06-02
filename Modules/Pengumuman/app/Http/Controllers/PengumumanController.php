<?php

namespace Modules\Pengumuman\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Pengumuman\app\Models\Pengumuman;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pengumumans = Pengumuman::paginate(10);

        return view('pengumuman::index', compact('pengumumans'));
    }

    public function create()
    {
        return view('pengumuman::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'status' =>  ['required'],
        ], [
            'title.required' => 'Judul harus diisi',
            'content.required' => 'Pengumuman harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        $result =  Pengumuman::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status == 1 ? 'show' : 'hide',
        ]);

        if ($result) {

            if ($request->status == 1) {
                $users = User::where('role', 'student')->where('is_banned', 'no')->get();

                foreach ($users as $user) {
                    // Send notification
                    sendNotification([
                        'user_id' => $user->id,
                        'title' => $user->name,
                        'body' => "Pengumuman baru telah ditambahkan",
                        'link' => route('student.dashboard'),
                        'path' => [
                            'module' => 'pengumuman',
                            'id' => $result->id
                        ]
                    ]);
                }
            }


            return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan');
        }

        return redirect()->route('admin.pengumuman.index')->with('error', 'Pengumuman gagal ditambahkan');
    }

    /**
     * Show the specified resource.
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        if (!$pengumuman) {
            return redirect()->route('admin.pengumuman.index')->with('error', 'Pengumuman tidak ditemukan');
        }
        return view('pengumuman::edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'status' =>  ['required'],
        ], [
            'title.required' => 'Judul harus diisi',
            'content.required' => 'Pengumuman harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);

        if (!$pengumuman) {
            return redirect()->route('admin.pengumuman.index')->with('error', 'Pengumuman tidak ditemukan');
        }

        $pengumuman->title = $request->title;
        $pengumuman->content = $request->content;
        $pengumuman->status = $request->status == 1 ? 'show' : 'hide';

        $result = $pengumuman->save();

        if (!$result) {
            return redirect()->route('admin.pengumuman.index')->with('error', 'Pengumuman gagal diperbarui');
        }

        if ($request->status == 1) {
                $users = User::where('role', 'student')->where('is_banned', 'no')->get();

                foreach ($users as $user) {
                    // Send notification
                    sendNotification([
                        'user_id' => $user->id,
                        'title' => $user->name,
                        'body' => "Pengumuman baru telah ditambahkan",
                        'link' => route('student.dashboard'),
                        'path' => [
                            'module' => 'pengumuman',
                            'id' => $pengumuman->id
                        ]
                    ]);
                }
            }

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui');
    }
}
