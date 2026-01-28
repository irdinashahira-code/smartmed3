<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\AdInteraction;
use Illuminate\Support\Facades\Storage;

class AdminAdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $advertisements = Advertisement::orderBy('priority', 'desc')->paginate(10);
        return view('admin.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:preventive,health_tip,service_promotion,doctor_highlight',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('advertisements', 'public');
            $data['image_path'] = $path;
        }

        // Convert target_conditions to array if needed (though cast handles array, input might be array)
        if ($request->has('target_conditions')) {
             $data['target_conditions'] = $request->target_conditions; 
        }

        Advertisement::create($data);

        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement created successfully.');
    }

    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }

    public function update(Request $request, Advertisement $advertisement)
    {
         $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:preventive,health_tip,service_promotion,doctor_highlight',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|integer|min:1|max:10',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($advertisement->image_path) {
                Storage::disk('public')->delete($advertisement->image_path);
            }
            $path = $request->file('image')->store('advertisements', 'public');
            $data['image_path'] = $path;
        }

        if ($request->has('target_conditions')) {
            $data['target_conditions'] = $request->target_conditions; 
       }

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement updated successfully.');
    }

    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->image_path) {
            Storage::disk('public')->delete($advertisement->image_path);
        }
        $advertisement->delete();
        return redirect()->route('admin.advertisements.index')->with('success', 'Advertisement deleted successfully.');
    }

    public function analytics()
    {
        $totalImpressions = AdInteraction::where('interaction_type', 'impression')->count();
        $totalClicks = AdInteraction::where('interaction_type', 'click')->count();
        $ctr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
        
        $adStats = Advertisement::withCount(['interactions as impressions' => function($query) {
                $query->where('interaction_type', 'impression');
            }, 'interactions as clicks' => function($query) {
                $query->where('interaction_type', 'click');
            }])
            ->get()
            ->map(function($ad) {
                $ad->ctr = $ad->impressions > 0 ? ($ad->clicks / $ad->impressions) * 100 : 0;
                return $ad;
            })
            ->sortByDesc('ctr');

        return view('admin.advertisements.analytics', compact('totalImpressions', 'totalClicks', 'ctr', 'adStats'));
    }
}
