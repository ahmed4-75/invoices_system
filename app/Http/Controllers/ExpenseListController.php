<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseList;
use App\Http\Resources\ExpenseListResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExpenseListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = ExpenseList::get();
        $expenses = ExpenseListResource::collection($expenses)->resolve();
        return view('expense.expense_list',compact('expenses'));
    }

    /**
     * Display the attachments.
     */
    public function attachments($creditorName,$fileName){
        $fileName = urldecode($fileName);
        $path = 'attachments/' . Str::slug($creditorName, '_') . '/'.'expense/' . ($fileName ?? null);

        if (!$fileName || !Storage::disk('public')->exists($path)) {
            abort(404);
        }
        $absolutePath = Storage::disk('public')->path($path);

        return response()->file($absolutePath);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'expense_number' => 'required|integer|unique:expense_lists|min:0',
            'creditor_name' => 'required|regex:/^[\p{L}\p{M}\s]+$/u',
            'expense_value' => 'required|decimal:2|min:0',
            'pay_date' => 'required|date_format:Y-m-d',
            'notes' => 'string',
            'attaches' => 'required|array',
            'attaches.*' => 'required|file|mimes:pdf,jpeg,jpg,png|max:6120'
        ],[
            'expense_number.required' => 'Enter Expense Number .',
            'expense_number.integer' => 'Enter the CORRECT NUMBER .',
            'expense_number.unique' => 'This Number has been USED before .',
            'expense_number.min' => 'Enter the CORRECT NUMBER .',
            'creditor_name.required' => 'Enter the Creditor Name .',
            'creditor_name.regex' => 'Enter Creditor Name CORRECTLY .',
            'expense_value.required' => 'Enter the Expense Value .',
            'expense_value.decimal' => 'Enter the CORRECT Value LIKE 0.00 .',
            'expense_value.min' => 'Enter the CORRECT Value Minimum 0.00 .',
            'pay_date.required' => 'Enter Pay Date .',
            'pay_date.date_format' => 'Enter by the CORRECT Format Date .',
            'attaches.required' => 'UPLOAD the Files .',
            'attaches.array' => 'Attachments must be sent as an array .',
            'attaches.*.required' => 'UPLOAD the Files .',
            'attaches.*.file' => 'Each attachment must be a valid file .',
            'attaches.*.mimes' => 'Attachments must be PDF, JPG, JPEG or PNG .',
            'attaches.*.max' => 'Each attachment must not exceed 6 MB .'
        ]);
        // dd($request->all());
        $filesNames = null;
        if($request->hasFile('attaches')){
            $folderName=Str::slug($request->creditor_name,'_');

            $filesNames=collect($request->file('attaches'))
                ->map(function($file) use($request,$folderName) {
                    $fileName=now()->format('Ymd-His')."_{$request->expense_number}_". 
                        preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                    $file->storeAs("public/attachments/expense/{$folderName}/",$fileName);
                    return $fileName;
                })->implode(',');
        }
        $expense =ExpenseList::create([
            'user' => Auth::user()->name,
            'expense_number' => $request->expense_number,
            'creditor_name' => $request->creditor_name,
            'expense_value' => $request->expense_value,
            'status' => 'Unpaid', 
            'value_status' => 0,
            'notes' => $request->notes,
            'pay_date' => $request->pay_date,
            'file_name' => $filesNames
        ]);

        session()->flash('add','The Expense has been Created Successfully ✅');
        return to_route('expense_list.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseList $expenseList)
    {
        dd($request->all());
        $request->validate([
            'pay_date' => 'required|date_format:Y-m-d',
            'notes' => 'string'
        ],[
            'pay_date.required' => 'Enter Pay Date .',
            'pay_date.date_format' => 'Enter by the CORRECT Format Date .'
        ]);

        $filesNames = $expenseList->file_name;
        if($request->hasFile('attaches')){
            $folderName=Str::slug($expenseList->creditor_name,'_');

            $newFilesNames=collect($request->file('attaches'))
                ->map(function($file) use($expenseList,$folderName) {
                    $newFileName=now()->format('Ymd-His')."_{$expenseList->expense_number}_". 
                        preg_replace('/[^A-Za-z0-9_\p{Arabic}\.-]/u', '_', $file->getClientOriginalName());
                    $file->storeAs("public/attachments/{$folderName}/expense/",$newFileName);
                    return $newFileName;
                })->implode(',');

            if(!empty($filesNames)){
                $filesNames .= ','.$newFilesNames;
            }else{
                $filesNames = $newFilesNames;
            }
        }

        $expenseList->update([
            'pay_date' => $request->pay_date,
            'notes' => $request->notes,
            'file_name' => $filesNames
        ]);

        session()->flash('edit','The Expense has been Edited Successfully ✅');
        return to_route('expense_list.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseList $expenseList)
    {
        $expense = $expenseList;
        if($expense->value_status == 1){
            $expense->delete();
        
            session()->flash('delete', 'Expense has been Deleted Successfully ✅');
            return to_route('expense_list.index');
        }else{
            return to_route('expense_list.index')->withErrors([
                'error' => "This Expense can't be Deleted, Because It is not PAID"
            ]);
        }
    }
}
