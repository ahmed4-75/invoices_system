<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoicesList;
use App\Http\Resources\InvoicesListResource;

class InvoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $invoices = InvoicesList::select('id','invoice_number','customer_name','total_value','due_date','notes','invoice_date','updated_at','file_name')->onlyTrashed()->get();
        $invoices = InvoicesListResource::collection($invoices)->resolve();
        // dd($invoices);
        return view('archives.invoicesArchive',compact('invoices'));
    }

    /**
     * Restoring specified invoice and receipts from Archives.
    */
    public function restore(string $id){
        $invoice = InvoicesList::onlyTrashed()->findOrFail($id);
        $invoice->restore();
        $invoice->receipts()->withTrashed()->restore();
        return to_route('invoices_archive.index');
    }

    /**
     * Remove the specified invoice and receipts from storage.
    */
    public function destroy(string $id)
    {
        $invoice = InvoicesList::onlyTrashed()->findOrFail($id);
        $invoice->receipts()->withTrashed()->forceDelete();
        $invoice->forceDelete();
        return to_route('invoices_archive.index');
    }
} 