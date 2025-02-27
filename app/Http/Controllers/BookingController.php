<?php

namespace App\Http\Controllers;

use App\Models\BookingClass;
use App\Models\Classmodel;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Peminjaman';
        $class = Classmodel::all();

        return view('booking.index', compact('pageTitle', 'class'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $pageTitle = 'Form Peminjaman';
        $class = Classmodel::findOrFail($id);

        return view('booking.create', compact('pageTitle', 'class'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => auth()->user()->name,
            'classmodel_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'organization' => 'required',
            'activity_name' => 'required',
            'full_name' => 'required',
            'nim' => 'required',
            'semester' => 'required',
            'prodi' => 'required',
            'telp' => 'required',
            'no_letter' => 'required',
            'date_letter' => 'required',
            'signature' => 'required|file|max:2048',
            'apply_letter' => 'required|file|max:2048',
            'activity_proposal' => 'required|file|max:2048',
        ]);

        $filePaths = [];

        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $filename1 = time() . '_1_' . $signature->getClientOriginalName();
            $signature->move(public_path('uploads'), $filename1);
            $filePaths['signature'] = 'uploads/' . $filename1;
        }

        if ($request->hasFile('apply_letter')) {
            $apply_letter = $request->file('apply_letter');
            $filename2 = time() . '_2_' . $apply_letter->getClientOriginalName();
            $apply_letter->move(public_path('uploads'), $filename2);
            $filePaths['apply_letter'] = 'uploads/' . $filename2;
        }

        if ($request->hasFile('activity_proposal')) {
            $activity_proposal = $request->file('activity_proposal');
            $filename3 = time() . '_3_' . $activity_proposal->getClientOriginalName();
            $activity_proposal->move(public_path('uploads'), $filename3);
            $filePaths['activity_proposal'] = 'uploads/' . $filename3;
        }

        // dd($request->all());
        BookingClass::create([
            'user_id' => auth()->user()->id,
            'classmodel_id' => $request->classmodel_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'organization' => $request->organization,
            'activity_name' => $request->activity_name,
            'full_name' => $request->full_name,
            'nim' => $request->nim,
            'semester' => $request->semester,
            'prodi' => $request->prodi,
            'telp' => $request->telp,
            'no_letter' => $request->no_letter,
            'date_letter' => $request->date_letter,
            'signature' => $filePaths['signature'] ?? null,
            'apply_letter' => $filePaths['apply_letter'] ?? null,
            'activity_proposal' => $filePaths['activity_proposal'] ?? null,
        ]);
        return redirect()->route('booking.index')->with(['success' => 'Data Berhasil Disimpan!']);
        // $sig = time() . '.' . $request->signature->extension();
        // $apply = time() . '.' . $request->apply_letter->extension();
        // $activity = time() . '.' . $request->activity_proposal->extension();

        // $request->signature->move(public_path('uploads'), $sig);
        // $request->apply_letter->move(public_path('uploads'), $apply);
        // $request->activity_proposal->move(public_path('uploads'), $activity);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
