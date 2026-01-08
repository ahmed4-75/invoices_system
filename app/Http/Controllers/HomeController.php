<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoicesList;
use App\Models\ReceiptsList;
use App\Models\ExpenseList;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalInvoicesPaid = InvoicesList::where('value_status',2)->sum('total_value');
        $totalInvoicesUnpaid = InvoicesList::where('value_status',0)->sum('total_value');
        $totalInvoicesPartlyPaid = InvoicesList::where('value_status',1)->sum('total_value');
        $totalInvoices = InvoicesList::sum('total_value');
        $totalReceipts = ReceiptsList::sum('value');
        $totalExpenses = ExpenseList::sum('expense_value');
        return view('home',compact(
            'totalInvoicesPaid',
            'totalInvoicesUnpaid',
            'totalInvoicesPartlyPaid',
            'totalInvoices','totalReceipts','totalExpenses'));
    }
}
