<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function get_invoices()
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function search_invoice(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            $invoices = Invoice::with('customer')->where('id', 'LIKE', "%$search%")->get();
            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else {
            return $this->get_invoices();
        }
    }

    public function add_invoice(Request $request)
    {
        $counter = Counter::where('key', 'invoice')->first();
        $invoice = Invoice::orderBy('id', 'DESC')->first();

        if ($invoice) {
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        } else {
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix . $counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms And Condition',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1

            ]
        ];
        return response()->json($formData);
    }

    public function get_customers()
    {

        $customers = Customer::orderBy('id', 'DESC')->get();

        return response()->json([
            'customers' => $customers
        ], 200);
    }

    public function get_products()
    {
        $products = Product::orderBy('id', 'DESC')->get();

        return response()->json([
            'products' => $products
        ], 200);
    }

    public function store(Request $request)
    {

        $invoiceItem = $request->input('invoice_items');

        $invoiceData['sub_total'] = $request->input("subtotal");
        $invoiceData['total'] = $request->input("total");
        $invoiceData['customer_id'] = $request->input("customer_id");
        $invoiceData['number'] = $request->input("number");
        $invoiceData['date'] = $request->input("date");
        $invoiceData['due_date'] = $request->input("due_date");
        $invoiceData['discount'] = $request->input("discount");
        $invoiceData['reference'] = $request->input("reference");
        $invoiceData['terms_and_conditions'] = $request->input("terms_and_conditions");

        $invoice = Invoice::create($invoiceData);


        foreach (json_decode($invoiceItem) as $item) {

            $itemData["invoice_id"] = $invoice->id;
            $itemData["product_id"] = $item->id;
            $itemData["unit_price"] = $item->unit_price;
            $itemData["quantity"] = $item->quantity;

            InvoiceItem::create($itemData);
        }
    }

    public function get_invoice($id)
    {
        $invoice = Invoice::with(['customer', 'invoice_items.product'])->find($id);
        return response()->json([
            'invoice' => $invoice
        ], 200);
    }
    public function delete_invoice_items($id)
    {
        $invoiceItems = InvoiceItem::findOrFail($id);
        $invoiceItems->delete();
    }

    public function update_invoice(Request $request, $id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->number = $request->number;
        $invoice->reference = $request->reference;
        $invoice->discount = $request->discount;
        $invoice->terms_and_conditions = $request->terms_and_conditions;

        $invoice->update($request->all());


        $invoiceItems = $request->input("invoice_items");
        $invoice->invoice_items()->delete();

        foreach (json_decode($invoiceItems) as $item) {

            $itemData["invoice_id"] = $invoice->id;
            $itemData["product_id"] = $item->product_id;
            $itemData["unit_price"] = $item->unit_price;
            $itemData["quantity"] = $item->quantity;

            InvoiceItem::create($itemData);
        }
    }

    public function delete_invoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_items()->delete();
        $invoice->delete();
    }
}