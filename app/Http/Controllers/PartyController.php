<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartyController extends Controller
{
    public function index(Request $request)
    {
    $type = $request->get('type');
    $search = $request->get('search');
    
    $parties = Party::with('creator')
        ->where('created_by', Auth::id())
        ->when($type, function($query, $type) {
            return $query->where('type', $type);
        })
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10);
    
    return view('parties.index', compact('parties', 'type', 'search'));
}

public function searchSuggestions(Request $request)
{
    $search = $request->get('q');
    $type = $request->get('type');
    
    $parties = Party::where('created_by', Auth::id())
        ->when($type, function($query, $type) {
            return $query->where('type', $type);
        })
        ->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        })
        ->limit(10)
        ->get(['id', 'name', 'phone', 'type']);
    
    return response()->json($parties);
}

    public function create()
    {
        return view('parties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:customer,supplier,employee,driver',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Party::create([
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('parties.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Party $party)
    {
        return view('parties.edit', compact('party'));
    }

    public function update(Request $request, Party $party)
    {
        $request->validate([
            'type' => 'required|in:customer,supplier,employee,driver',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $party->update([
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('parties.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return redirect()->route('parties.index')->with('success', 'Data berhasil dihapus');
    }
}