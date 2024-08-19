@if ($payment)
    <div class="mt-4">
        <p>
            <span>{{ trans('plugins/payment::payment.payment_id') }}: </span>
            <strong>{{ $payment['id'] }}</strong>
        </p>
        <p>{{ trans('plugins/payment::payment.amount') }}: <strong>{{ $payment['amount']['value'] }} {{ $payment['amount']['currency'] }}</strong></p>

        @if ($payment['payment_method'] && $payment['payment_method']['type'] === 'bank_card' && isset($payment['payment_method']['card_type']))
            <p class="mb-2">{{ trans('plugins/payment::payment.card') }}: <strong>{{ $payment['payment_method']['card_type'] }} - **** **** **** {{ $payment['payment_method']['last4'] }}
                    - {{ $payment['payment_method']['expiry_year'] }}/{{ $payment['payment_method']['expiry_month'] }}</strong></p>
        @endif

        <hr>

        @if ($refunds = Arr::get($paymentModel->metadata, 'refunds', []))
            <h6 class="alert-heading">{{ trans('plugins/payment::payment.amount_refunded') }}:
                {{ collect($refunds)->sum('_data_request.refund_amount') }} {{ Arr::get(Arr::first($refunds), '_data_request.currency') }}</h6>
            @foreach ($refunds as $refund)
                <div id="{{ Arr::get($refund, 'data.id') }}">
                    @include('plugins/yoomoney::refund-detail')
                </div>
            @endforeach
        @endif

        @include('plugins/payment::partials.view-payment-source')
    </div>
@endif
