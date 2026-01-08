<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Product;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ProductResource;

class GoodsArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::onlyTrashed()->get();
        $sections = SectionResource::collection($sections)->resolve();

        $products = Product::select('id','product_name','description','value','created_at','updated_at','section_id')->onlyTrashed()
                    ->with('section:id,section_name')->get();
        $products = ProductResource::collection($products)->resolve();
        // dd($products);
        return view('archives.goodsArchive',compact('sections','products'));
    }

    /**
     * Restoring specified product from Archives.
     */
    public function product_restore(string $id){
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return to_route('goods_archive.index');
    }

    /**
     * Remove the specified product from storage.
     */
    public function product_destroy(string $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        return to_route('goods_archive.index');
    }

    /**
     * Restoring specified section from Archives.
     */
    public function section_restore(string $id){
        $section = Section::onlyTrashed()->findOrFail($id);
        $section->restore();
        return to_route('goods_archive.index');
    }

    /**
     * Remove the specified section from storage.
     */
    public function section_destroy(string $id)
    {
        $section = Section::onlyTrashed()->findOrFail($id);
        $section->forceDelete();
        return to_route('goods_archive.index');
    }
}
