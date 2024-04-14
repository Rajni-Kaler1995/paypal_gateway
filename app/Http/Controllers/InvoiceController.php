<?php

namespace App\Http\Controllers;
use App\Models\Uom;
use App\Models\PartyMaster;
use App\Models\ItemMaster;
use App\Models\Dochdr;
use App\Models\Docdet;
use Illuminate\Http\Request;
use DB;

class InvoiceController extends Controller
{
    function index(){
        $data['dochdr'] = Dochdr::selectRaw('dochdr.*, party_master.PARTY_NAME')
        ->join('party_master', 'party_master.PARTY_ID', '=', 'dochdr.Party_ID')
        ->orderBy('dochdr.HDRAutoID', 'desc') 
        ->get();
       
        return view('invoice.index', $data);
    }

    function addInvoice(){
        $data['uom'] = Uom::all();
        $data['ItemMaster'] = ItemMaster::all();
        $data['PartyMaster'] = PartyMaster::all();
        return view('invoice.add-invoice', $data);
    }

    public function getUnits($itemId)
    {
        // Assuming you have a relationship between Item and Unit models
        $item = ItemMaster::where('ITEM_ID', $itemId)->first();
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $units = Uom::where('UOM_ID', $item->UID)->first();

        // Return the units as JSON
        return response()->json($units);
    }

    public function insertInvoice(Request $request)
    {
        // Validate the request data
        $request->validate([
            'grandTotal' => 'required|numeric',
            'partyId' => 'required|exists:party_master,PARTY_ID',
            'invoiceNumber' => 'required|string',
            'invoiceDate' => 'required|date',
            // Add validation rules for other fields if needed
        ], [
            'partyId.required' => 'The party ID field is required.',
            'partyId.exists' => 'The selected party ID is invalid.',
            'invoiceNumber.required' => 'The invoice number field is required.',
            'invoiceNumber.string' => 'The invoice number must be a string.',
            'invoiceDate.required' => 'The invoice date field is required.',
            'invoiceDate.date' => 'The invoice date must be a valid date format.',
        ]);
    
        // Start a database transaction
      //  DB::beginTransaction();
    
       // try {
            // Insert data into the 'dochdr' table
            $dochdr = new Dochdr();
            $dochdr->Party_ID = $request->input('partyId');
            $dochdr->Invoice_No = $request->input('invoiceNumber');
            $dochdr->InvoiceDate = $request->input('invoiceDate');
            $dochdr->TotalAmount = $request->input('grandTotal');
            $dochdr->User = 1;
            $dochdr->DefaultDateTime = date('Y-m-d H:i:s');
            $dochdr->save();
    
            // Insert data into the 'docdet' table
            foreach ($request->items as $item) {
                $docdet = new Docdet();
                $docdet->HDRAuto_ID = $dochdr->HDRAutoID; // Use the ID from the inserted row in 'dochdr'
                $docdet->Item_ID = $item['itemId'];
                $docdet->UOM_ID = $item['uomId'];
                $docdet->Qty = $item['quantity'];
                $docdet->Rate = $item['rate'];
                $docdet->Value = $item['value'];
                $docdet->save();
            }
    
            // Commit the transaction
         //   DB::commit();
           // return redirect()->route('invoice.index');
        //     return redirect()->back()->with('success', 'Invoice submitted successfully!');
        // } catch (\Exception $e) {
        //     // Rollback the transaction in case of an error
        //     DB::rollback();
        //     return redirect()->back()->with('error', 'Failed to submit invoice. ' . $e->getMessage());
        // }
    }
}
