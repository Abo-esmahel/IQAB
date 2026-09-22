<?php

namespace App\Http\Controllers;

use App\Enums\PhoneNumberStatus;
use App\Http\Requests\CatalogFilterRequest;
use App\Models\PhoneNumber;

class NumberController extends Controller
{
    public function index(CatalogFilterRequest $request)
    {
        $f = $request->filters();
        $query = PhoneNumber::query()->where('status', PhoneNumberStatus::Available);

        if (! empty($f['country'])) {
            $query->where('country', $f['country']);
        }

        if (isset($f['min_price'])) {
            $query->where('price', '>=', $f['min_price']);
        }

        if (isset($f['max_price'])) {
            $query->where('price', '<=', $f['max_price']);
        }

        match ($f['sort']) {
            'price_asc' => $query->orderBy('price')->orderByDesc('created_at'),
            'price_desc' => $query->orderByDesc('price')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $numbers = $query->paginate(12)->withQueryString();

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
