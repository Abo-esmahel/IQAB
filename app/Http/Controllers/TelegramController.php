<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramServiceRequestForm;
use App\Mail\TelegramRequestStatusMail;
use App\Models\TelegramService;
use App\Models\TelegramServiceRequest;
use App\Services\Telegram\TelegramProviderService;
use App\Enums\TelegramServiceStatus;
use App\Enums\TelegramServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TelegramController extends Controller
{
    public function index(Request $request)
    {
        $services = TelegramService::where('is_active', true)->orderBy('price')->get();

        $recent = TelegramServiceRequest::where('user_id', $request->user()->id)
            ->with('telegramService')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $stats = [
            'total' => TelegramServiceRequest::where('user_id', $request->user()->id)->count(),
            'completed' => TelegramServiceRequest::where('user_id', $request->user()->id)
                ->where('status', TelegramServiceStatus::Completed->value)->count(),
        ];

        return view('telegram.index', compact('services', 'recent', 'stats'));
    }

    public function submit(TelegramServiceRequestForm $request, TelegramProviderService $telegram)
    {
        $service = TelegramService::where('id', $request->telegram_service_id)
            ->where('is_active', true)
            ->firstOrFail();
        $user = $request->user();
        $price = (float) $service->price;

        $serviceRequest = TelegramServiceRequest::create([
            'user_id' => $user->id,
            'telegram_service_id' => $service->id,
            'target_identifier' => $request->target_identifier,
            'price' => $price,
            'status' => TelegramServiceStatus::Processing->value,
        ]);

        try {
            $apiResult = match($service->type) {
                TelegramServiceType::AccountLookup => $telegram->lookupAccount($request->target_identifier),
                TelegramServiceType::AccountReport => $telegram->reportAccount($request->target_identifier, 'Reported via IQAB platform'),
                TelegramServiceType::AccountInformation => $telegram->getAccountInformation($request->target_identifier),
                default => [],
            };

            if (empty($apiResult)) {
                $serviceRequest->update([
                    'status' => TelegramServiceStatus::Failed->value,
                    'error_message' => 'Provider returned an empty result.',
                ]);
            } else {
                $serviceRequest->update([
                    'status' => TelegramServiceStatus::Completed->value,
                    'result' => $apiResult,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram service failed', ['error' => $e->getMessage()]);
            $serviceRequest->update([
                'status' => TelegramServiceStatus::Failed->value,
                'error_message' => $e->getMessage(),
            ]);
        }

        Mail::to($user)->queue(new TelegramRequestStatusMail($user, $serviceRequest->fresh()));

        return redirect()->route('telegram.result', $serviceRequest)
            ->with('success', 'Service request submitted!');
    }

    public function result(TelegramServiceRequest $request)
    {
        $this->authorize('view', $request);

        return view('telegram.result', ['request' => $request]);
    }

    public function history(Request $request)
    {
        $requests = TelegramServiceRequest::where('user_id', $request->user()->id)
            ->with('telegramService')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('telegram.history', compact('requests'));
    }
}
