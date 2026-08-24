<?php

namespace App\Http\Controllers;

use App\Enums\PhoneNumberStatus;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;

class NumberController extends Controller
{
    public function index(Request $request)
    {
        $query = PhoneNumber::query()->where('status', PhoneNumberStatus::Available);

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }


        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $numbers = $query->orderByDesc('created_at')->paginate(12);

        $countries = PhoneNumber::where('status', PhoneNumberStatus::Available)->distinct()->pluck('country')->filter()->values();

        return view('numbers.index', compact('numbers', 'countries'));
    }

    public function show(PhoneNumber $number)
    {
        if ($number->status !== PhoneNumberStatus::Available) {
            abort(404);
        }

        return view('numbers.show', ['number' => $number]);
    }
}
