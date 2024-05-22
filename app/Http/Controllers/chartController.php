<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaction;

class chartController extends Controller
{
    //
    public function barChart()
    {
        // Replace this with your actual data retrieval logic
        $transactions = transaction::select('from', 'to')->get();
        $data = [
            // 'labels' => DummyData::all()
            'labels' => [$transactions->pluck('name')],
            'datas' => [1, 0, 2, 4, 20],
        ];
        // return response()->json(['message' => $data]);
        return view('barChart', compact('data'));
    }
}
