<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Product;
use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::withExists('products')->get();
        $sections = SectionResource::collection($sections)->resolve();
        // dd($sections);

        return view('goods.sections',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
                'section_name' => 'required|unique:sections|max:255',
                'description'=>'required'
            ],[
                'section_name.unique'=>'The Section is Pre Added .'
            ]);

            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name
            ]);
            session()->flash('add','Section Added Successfully');
            
            return to_route('sections.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description'=>'required'
        ],[
            'section_name.unique'=>'The Section is Pre Added .'
        ]);

        $section=Section::findOrFail($id);
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => Auth::user()->name
        ]);
        session()->flash('edit','Section Edited Successfully');

        return to_route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        $section = Section::findOrFail($id);
        if ($section->products()->exists()) {
            return to_route('sections.index')->withErrors([
                'error' => 'This Section cannot be Deleted, it has related products'
            ]);
        }
        $section->delete();
        session()->flash('delete','Section deleted Successfully');

        return to_route('sections.index');
    }
}
