<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user ? $user->profile : null;
        $tab = $request->query('tab', 'recommended');
        $query = $request->query('query');

        $items = collect();


        if ($tab === 'recommended') {
            $itemQuery = Item::query();
            if ($user) {
                $itemQuery->where('user_id', '!=', $user->id);
            }
            if (!empty($query)) {
                $itemQuery->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                });
            }
            $items = $itemQuery->get();
        } elseif ($tab === 'mylist' && $user) {
            $items = $user->likedItems()
            ->where(function ($q) use ($query) {
                if (!empty($query)) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                }
            })
            ->get();
        }
            return view('top', compact('profile', 'tab', 'query', 'items'));
        }
    }