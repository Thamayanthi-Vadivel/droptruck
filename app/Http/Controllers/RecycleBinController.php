<?php
// app/Http/Controllers/RecycleBinController.php

namespace App\Http\Controllers;

use App\Models\RecycledIndent;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function index()
    {
        $recycledIndents = RecycledIndent::with('indentRate')->get();
        return view('recycle_bin.index', compact('recycledIndents'));
    }

    public function restore($id)
    {
        $recycledIndent = RecycledIndent::findOrFail($id);

        // Restore the indent (you may need to adjust this based on your actual implementation)
        $restoredIndent = $recycledIndent->restoreIndent();

        return redirect()->route('recycle_bin.index')->with('success', 'Indent restored successfully!');
    }

    public function destroy($id)
    {
        $recycledIndent = RecycledIndent::findOrFail($id);
        $recycledIndent->delete();

        return redirect()->route('recycle_bin.index')->with('success', 'Indent permanently deleted!');
    }
}

