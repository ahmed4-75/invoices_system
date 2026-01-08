<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseList;
use App\Http\Resources\ExpenseListResource;

class ExpensesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $expenses = ExpenseList::select('id','expense_number','creditor_name','expense_value','pay_date','notes','expense_date','updated_at','file_name')->onlyTrashed()->get();
        $expenses = ExpenseListResource::collection($expenses)->resolve();
        // dd($expenses);
        return view('archives.expensesArchive',compact('expenses'));
    }

    /**
     * Restoring specified expense from Archives.
    */
    public function restore(string $id){
        $expense = ExpenseList::onlyTrashed()->findOrFail($id);
        $expense->restore();
        return to_route('expenses_archive.index');
    }

    /**
     * Remove the specified expense from storage.
    */
    public function destroy(string $id)
    {
        $expense = ExpenseList::onlyTrashed()->findOrFail($id);
        $expense->forceDelete();
        return to_route('expenses_archive.index');
    }
} 