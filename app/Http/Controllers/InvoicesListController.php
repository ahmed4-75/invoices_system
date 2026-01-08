<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicesList;
use App\Models\Product;
use App\Models\Section;
use App\Models\ReceiptsList;
use App\Models\ExpenseList;
use App\Http\Resources\InvoicesListResource;
use App\Http\Resources\ProductResource;
use App\Http\Requests\InvoicesListRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoicesListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = InvoicesList::select('id','invoice_number','invoice_date', 'customer_name','invoice_value','discount_value','vat_value','total_value','due_date','updated_at','status','value_status','notes')->get();
        $invoices = InvoicesListResource::collection($invoices)->resolve();
        // return $invoices;
        return view('invoices.invoices_list',compact('invoices'));
    }

    /**
     * Selecting Products names Related to one Section 
     */
    public function get_products($id)
    {
        $section =Section::find($id);
        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }
        $products = $section->products()->select('id', 'product_name','quantity','value')->get();

        return response()->json(ProductResource::collection($products));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections=Section::select('id','section_name')->get();

        return view('invoices.create_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoicesListRequest $request)
    {
        // dd($request->all());
        $filesNames = null;
        if($request->hasFile('attaches')){
            $folderName=Str::slug($request->customer_name,'_');

            $filesNames=collect($request->file('attaches'))
                ->map(function($file) use($request,$folderName) {
                    $fileName=now()->format('Ymd-His')."_{$request->invoice_number}_". 
                        preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                    $file->storeAs("public/attachments/{$folderName}/invoices/",$fileName);
                    return $fileName;
                })->implode(',');
        }
        $invoice =InvoicesList::create([
            'user' => Auth::user()->name,
            'invoice_number' => $request->invoice_number,   #
            'customer_name' => $request->customer_name,  #
            'invoice_value' => $request->invoice_value,
            'discount_value' => $request->discount_value,
            'vat_value' => $request->vat_value,
            'total_value' => $request->total_value,   #
            'due_date' => $request->due_date,  #
            'status' => 'Unpaid',  #
            'value_status' => 0,
            'notes' => $request->notes,  #
            'file_name' => $filesNames
        ]);

        // ✅ ربط المنتجات مع الفاتورة وتخزين عدد الوحدات لكل منتج
        $products = $request->products;
        $units = $request->units;
        foreach ($products as $index => $productId) {
            $invoice->products()->attach($productId, ['units' => $units[$index] ?? 1,]);
            $product = Product::findOrFail($productId);
            $quantity = max(0,$product->quantity - ($units[$index] ?? 1));
            $product->update([ 'quantity' => $quantity ]);
        }

        session()->flash('add','The Invoice has been Created Successfully ✅');
        return to_route('invoices_list.index');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(InvoicesList $invoices_list)
    {
        $invoices_list->load([
            'products' => function ($q) {
                $q->select('products.id', 'products.product_name', 'products.description', 'products.value','products.section_id');
            },
            'products.section' => function ($q) {
                $q->select('sections.id', 'sections.section_name');
            },
            'receipts' => function ($q){
                $q->select('receipts_lists.value','receipts_lists.due_value','receipts_lists.invoice_id');
            }
        ]);
        $receiptsValue = $invoices_list->receipts->sum('value');
        $lastDueValue = $invoices_list->receipts->last()?->due_value ?? $invoices_list->total_value;    
        // $lastDueValue =$invoices_list->receipts->last() ? $invoices_list->receipts->last()->due_value : $invoices_list->total_value;        
        $invoice = new InvoicesListResource($invoices_list);
        // return $invoice;
        $invoice = $invoice->resolve();
        $invoice['receiptsValue'] = $receiptsValue;
        $invoice['totalDueValue'] = $lastDueValue;
        return view('invoices.show_invoice',compact(['invoice']));
    }

    /**
     * Display the attachments.
     */
    public function attachments($customerName,$fileName){
        $fileName = urldecode($fileName);
        $path = 'attachments/' . Str::slug($customerName, '_') . '/'.'invoices/' . ($fileName ?? null);

        if (!$fileName || !Storage::disk('public')->exists($path)) {
            abort(404);
        }
        $absolutePath = Storage::disk('public')->path($path);

        return response()->file($absolutePath);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,InvoicesList $invoices_list)
    {
        $invoice = $invoices_list;
        $user = Auth::user();
        if($invoice->value_status >= 2){
            return to_route('invoices_list.index')->withErrors([
                'error' => "This Invoice can't be Edited, Because It's PAID"
            ]);
        }else{
            if($user->hasRole('admin')){
                $request->validate([
                    'discount_value' => 'required|decimal:2|min:0',
                    'vat_value' => 'required|decimal:2|min:0',
                    'total_value' => 'required|decimal:2|min:0',
                    'due_date' => 'required|date_format:Y-m-d',
                    'notes' => 'string',
                    'attaches' => [Rule::requiredIf($request->total_value != $invoice->total_value),'array'],
                    'attaches.*' => 'file|mimes:pdf,jpeg,jpg,png|max:6120'
                ],[
                    'discount_value.required' => 'Enter the Discount Value',
                    'discount_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'discount_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'vat_value.required' => 'Enter the Vat Value',
                    'vat_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'vat_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'total_value.required' => 'Enter the Total Value',
                    'total_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'total_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'due_date.required' => 'Enter The Due Date',
                    'due_date.date_format' => 'Enter by the CORRECT Format Date .',
                    'attaches.required' => 'The Attachments is Required when Total Invoice Value is CHANGED',
                    'attaches.array' => 'Attachments must be sent as an array.',
                    'attaches.*.file' => 'Each attachment must be a valid file.',
                    'attaches.*.mimes' => 'Attachments must be PDF, JPG, JPEG or PNG.',
                    'attaches.*.max' => 'Each attachment must not exceed 6 MB.'
                ]);
            } elseif($user->hasRole('accountant')) {
                $request->validate([
                    'discount_value' => ['required|decimal:2|min:0',Rule::in([$invoice->discount_value])],
                    'vat_value' => ['required|decimal:2|min:0',Rule::in([$invoice->vat_value])],
                    'total_value' => ['required|decimal:2|min:0',Rule::in([$invoice->total_value])],
                    'due_date' => 'required|date_format:Y-m-d',
                    'notes' => 'string',
                    'attaches' => 'nullable|array',
                    'attaches.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:6120'
                ],[
                    'discount_value.required' => 'Enter the Discount Value',
                    'discount_value.in' => 'You are not Authorized to update the Discount Value',
                    'discount_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'discount_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'vat_value.required' => 'Enter the Vat Value',
                    'vat_value.in' => 'You are not Authorized to update the VAT Value',
                    'vat_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'vat_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'total_value.required' => 'Enter the Total Value',
                    'total_value.in' => 'You are not Authorized to update the Total Value',
                    'total_value.decimal' => 'Enter the CORRECT Value LIKE 0.00',
                    'total_value.min' => 'Enter the CORRECT Value Minimum 0.00',
                    'due_date.required' => 'Enter The Due Date',
                    'due_date.date_format' => 'Enter by the CORRECT Format Date .',
                    'attaches.array' => 'Attachments must be sent as an array.',
                    'attaches.*.file' => 'Each attachment must be a valid file.',
                    'attaches.*.mimes' => 'Attachments must be PDF, JPG, JPEG or PNG.',
                    'attaches.*.max' => 'Each attachment must not exceed 6 MB.'
                ]);
            } else {
                abort(403, 'Unauthorized');
            }
            $valueStatus = $invoice->value_status;
            $NewInvoiceValue = $request->total_value;
            $OldInvoiceValue = $invoice->total_value;
            $totalReceiptsValue = $invoice->receipts()->sum('value');
            dd($totalReceiptsValue);
            
            $filesNamesI = $invoice->file_name;
            if($request->hasFile('attaches')){
                $folderName=Str::slug($invoice->customer_name,'_');
    
                $newFilesNamesI=collect($request->file('attaches'))
                    ->map(function($file) use($invoice,$folderName) {
                        $newFileNameI=now()->format('Ymd-His')."_{$invoice->invoice_number}_". 
                            preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                        $file->storeAs("public/attachments/{$folderName}/invoices/",$newFileNameI);
                        return $newFileNameI;
                    })->implode(',');
    
                if(!empty($filesNamesI)){
                    $filesNamesI .= ','.$newFilesNamesI;
                }else{
                    $filesNamesI = $newFilesNamesI;
                }
            }
            if($valueStatus == 0 or $NewInvoiceValue == $OldInvoiceValue){
                $invoice->update([
                    'discount_value' => $request->discount_value,
                    'vat_value' => $request->vat_value,
                    'total_value' => $NewInvoiceValue,
                    'due_date' => $request->due_date,
                    'notes' => $request->notes,
                    'file_name' => $filesNamesI
                ]);
            }elseif ($valueStatus == 1 and $NewInvoiceValue != $OldInvoiceValue) {    
                $receipt = $invoice->receipts()->latest()->first();
                $filesNamesR = $receipt?->file_name;
                if($request->hasFile('attaches')){
                    $folderName=Str::slug($invoice->customer_name,'_');
        
                    $newFilesNamesR=collect($request->file('attaches'))
                        ->map(function($file) use($invoice,$folderName) {
                            $newFileNameR=now()->format('Ymd-His')."_{$invoice->invoice_number}_". 
                                preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                            $file->storeAs("public/attachments/{$folderName}/receipts/",$newFileNameR);
                            return $newFileNameR;
                        })->implode(',');
        
                    if(!empty($filesNamesR)){
                        $filesNamesR .= ','.$newFilesNamesR;
                    }else{
                        $filesNamesR = $newFilesNamesR;
                    }
                }
                if($NewInvoiceValue > $totalReceiptsValue){
                    // dd('good');
                    $dueValue = $NewInvoiceValue - $totalReceiptsValue;
                    $invoice->update([
                        'discount_value' => $request->discount_value,
                        'vat_value' => $request->vat_value,
                        'total_value' => $NewInvoiceValue,
                        'due_date' => $request->due_date,
                        'notes' => $request->notes,
                        'file_name' => $filesNamesI
                    ]);
                    $receipt->update([
                        'due_value' => $dueValue,
                        'file_name' => $filesNamesR
                    ]);
                }
                elseif($NewInvoiceValue == $totalReceiptsValue){
                    // dd('good');
                    $invoice->update([
                        'discount_value' => $request->discount_value,
                        'vat_value' => $request->vat_value,
                        'total_value' => $NewInvoiceValue,
                        'due_date' => $request->due_date,
                        'notes' => $request->notes,
                        'status' => 'Paid',
                        'value_status' => 2,
                        'file_name' => $filesNamesI
                    ]);
                    $receipt->update([
                        'due_value' => 0,
                        'file_name' => $filesNamesR
                    ]);
                }
                elseif($NewInvoiceValue < $totalReceiptsValue){
                    // dd('good');
                    $invoice->update([
                        'discount_value' => $request->discount_value,
                        'vat_value' => $request->vat_value,
                        'total_value' => $NewInvoiceValue,
                        'due_date' => $request->due_date,
                        'notes' => $request->notes,
                        'status' => 'Paid',
                        'value_status' => 2,
                        'file_name' => $filesNamesI
                    ]);
                    $receipt->update([
                        'due_value' => 0,
                        'file_name' => $filesNamesR
                    ]);
    
                    $filesNamesE = null;
                    if($request->hasFile('attaches')){
                        $folderName=Str::slug($invoice->customer_name,'_');
            
                        $filesNamesE=collect($request->file('attaches'))
                            ->map(function($file) use($invoice,$folderName) {
                                $fileNameE=today()."_{$invoice->customer_name}_". 
                                    preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                                $file->storeAs("public/attachments/expenses/{$folderName}/",$fileNameE);
                                return $fileNameE;
                            })->implode(',');
                    }
                    $expenseValue = $totalReceiptsValue - $NewInvoiceValue;
                    ExpenseList::create([
                        'user' => Auth::user()->name,
                        'expense_number' => 'I-'.$invoice->invoice_number,
                        'creditor_name' => $invoice->customer_name,
                        'expense_value' => $expenseValue,
                        'status' => 'Unpaid', 
                        'value_status' => 0,
                        // 'notes' => ,
                        'pay_date' => today()->addDays(30),
                        'file_name' => $filesNamesE
                    ]);
                }
            }
            session()->flash('edit','The Invoice has been Edited Successfully ✅');
            return to_route('invoices_list.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = InvoicesList::findOrFail($id);
        $invoice->receipts()->delete();
        $invoice->delete();
        
        session()->flash('delete', 'Invoice and Receipts Deleted Successfully ✅');
        return to_route('invoices_list.index');
    }
}