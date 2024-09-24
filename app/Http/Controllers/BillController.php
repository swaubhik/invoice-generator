<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;

class BillController extends Controller
{
    // Show form to create bill for a specific application
    public function create($application_id)
    {
        $application = Application::findOrFail($application_id);
        return view('bills.create', compact('application'));
    }

    // Store bill data
    public function store(Request $request, $application_id)
    {
        $application = Application::findOrFail($application_id);

        // Validate bill items
        $request->validate([
            'items.*.description' => 'required|string',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate amounts
        $items = $request->input('items');
        $subtotal = 0;
        foreach ($items as &$item) {
            $item['amount'] = $item['rate'] * $item['quantity'];
            $subtotal += $item['amount'];
        }

        // Calculate GST and grand total
        $gst = $subtotal * 0.18; // 18% GST
        $grand_total = $subtotal + $gst;

        // Create Bill
        $bill = Bill::create([
            'application_id' => $application->id,
            'subtotal' => $subtotal,
            'gst' => $gst,
            'grand_total' => $grand_total,
        ]);

        // Create Bill Items
        foreach ($items as $item) {
            BillItem::create([
                'bill_id' => $bill->id,
                'description' => $item['description'],
                'rate' => $item['rate'],
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('bills.show', [$application->id, $bill->id])->with('success', 'Bill created successfully.');
    }

    // Show the bill
    public function show($application_id, $bill_id)
    {
        $application = Application::findOrFail($application_id);
        $bill = Bill::with('billItems')->findOrFail($bill_id);

        return view('bills.show', compact('application', 'bill'));
    }

    // Additional methods like index, edit, etc., can be added as needed
}
