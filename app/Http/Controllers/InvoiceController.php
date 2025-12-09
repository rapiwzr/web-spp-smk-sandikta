<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashTransaction; // Sesuaikan jika modelnya berbeda

class InvoiceController extends Controller
{
    public function show($id)
    {
        $transaction = CashTransaction::findOrFail($id);

        return view('invoice', compact('transaction'));
    }
}