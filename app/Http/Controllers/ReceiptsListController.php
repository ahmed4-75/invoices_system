<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoicesList;
use App\Models\ReceiptsList;
use App\Http\Resources\InvoicesListResource;
use App\Http\Resources\ReceiptsListResource;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;
use App\Notifications\ReceiptsPay;
use Illuminate\Support\Facades\Notification;

class ReceiptsListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipts = ReceiptsList::with(['invoice:id,invoice_number,customer_name,due_date,notes'])->get();
        $receipts = ReceiptsListResource::collection($receipts)->resolve();
        // dd($receipts);
        return view('receipts.receipts_list',compact( 'receipts'));
    }

    /**
     * Display the attachments.
     */
    public function attachments($customerName,$fileName){
        $fileName = urldecode($fileName);
        $path = 'attachments/' . Str::slug($customerName, '_') . '/'.'receipts/' . ($fileName ?? null);

        if (!$fileName || !Storage::disk('public')->exists($path)) {
            abort(404);
        }
        $absolutePath = Storage::disk('public')->path($path);

        return response()->file($absolutePath);
    }

    /**
     * Display a Specific Invoice to CREATE a Receipt.
    */    
    public function search_invoice(Request $request){
        $invoice = InvoicesList::where('invoice_number', $request->invoice_number)->where('customer_name', 'LIKE', "%".$request->customer_name."%")
            ->select('id','invoice_number','customer_name','total_value','due_date','status','value_status')->first();
            
        if (!$invoice) {
            return response()->json([
                'error' => true,
                'message' => 'Invoice not found'
            ]);
        } 
            //  إذا كانت الفاتورة مدفوعة جزئياً او كلياً
            if ($invoice->value_status != 0) {
                $invoice->load(['receipts' => function($q) use ($invoice){
                    $q->select('id','due_value','invoice_id')->where('invoice_id',$invoice->id)->orderBy('id','desc');
                }]);
            }

            return response()->json([
                'error' => false,
                'data' => (new InvoicesListResource($invoice))->resolve()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $invoice = InvoicesList::findOrFail($request->id);
        $receipt = $invoice->receipts()->latest()->first();
        $oldDueValue = $receipt ? $receipt->due_value : $invoice->total_value;

        $request->validate([
            'id' => 'required|integer|min:0',
            'value' => "required|decimal:2|min:0|max:$oldDueValue",
            'attaches' => 'nullable|array',
            'attaches.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ],[
            'id.required' => 'Select an Invoice',
            'id.integer' => 'Select an Invoice',
            'id.min' => 'Select an Invoice',
            'value.required' => 'Enter The Receipt Value',
            'value.decimal' => 'Enter The CORRECT Receipt Value LIKE 0.00',
            'value.min' => 'Enter The CORRECT Receipt Value Minimum 0.00',
            'value.max' => 'Enter The CORRECT Receipt Value Maximum or The Invoice has been Paid, The Due Value is '.$oldDueValue,
            'attaches.array' => 'Attachments must be sent as an array.',
            'attaches.*.file' => 'Each attachment must be a valid file.',
            'attaches.*.mimes' => 'Attachments must be PDF, JPG, JPEG or PNG.',
            'attaches.*.max' => 'Each attachment must not exceed 6 MB.'
        ]);
        $fileNames = null;
        if($request->hasFile('attaches')){
            $folderName=Str::slug($invoice->customer_name,'_');

            $fileNames=collect($request->file('attaches'))
                ->map(function($file) use($invoice,$folderName) {
                    $fileName=now()->format('Ymd-His')."_{$invoice->invoice_number}_". 
                        preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                    $file->storeAs("public/attachments/{$folderName}/receipts/",$fileName);
                    return $fileName;
                })->implode(',');
        }
        $newDueValue = $oldDueValue - $request->value;
        $receipt = ReceiptsList::create([
            'value' => $request->value,
            'due_value' => $newDueValue,
            'invoice_id' => $request->id,
            'file_name' =>$fileNames
        ]);
        if($newDueValue == 0){
            $invoice->update([
                'status' => 'Paid',
                'value_status' => 2
            ]);
        }else{
            $invoice->update([
                'status' => 'Partially Paid',
                'value_status' => 1
            ]);
        }
        session()->flash('add','The Receipt has been Created Successfully ✅');
        return to_route('receipts_list.index');
    }
}
