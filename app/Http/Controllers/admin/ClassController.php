<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Kelas';
        $classes = Classmodel::all();

        return view('admin.class.index', compact('pageTitle', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tambah Kelas';

        return view('admin.class.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ]);

        Classmodel::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $request->image,
        ]);

        return Redirect::back()->with(['success' => 'Success Store!']);
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
        $pageTitle = 'Edit Kelas';
        $classes = Classmodel::find($id);

        return view('admin.class.edit', compact('pageTitle', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'image' => 'required',
        ]);

        $classes = Classmodel::findOrFail($id);
        $classes->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'image' => $request->image,
        ]);

        return redirect()->route('class.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Classmodel::findOrFail($id)->delete();

        return redirect()->route('class.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
}
