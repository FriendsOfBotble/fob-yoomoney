@php $yoomoneyStatus = get_payment_setting('status', YOOMONEY_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/yoomoney/images/logo.png') }}"
                 alt="Yoomoney">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://yookassa.ru" target="_blank">{{ __('Yoomoney Checkout') }}</a>
                    <p>{{ __('Customer can buy product and pay directly using Visa, Credit card via :name', ['name' => 'Yoomoney']) }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div
                    class="payment-name-label-group @if (get_payment_setting('status', YOOMONEY_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', YOOMONEY_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($yoomoneyStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($yoomoneyStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', YOOMONEY_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Yoomoney']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Yoomoney']) }}:</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://yookassa.ru" target="_blank">
                                        {{ __('Register an account on :name', ['name' => 'Yoomoney']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have API & Secret keys', ['name' => 'Yoomoney Checkout']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter API key, Secret into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="yoomoney_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_name"
                                   id="yoomoney_name" data-counter="400"
                                   value="{{ get_payment_setting('name', YOOMONEY_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'Yoomoney'])) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_description" id="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_description">{{ get_payment_setting('description', YOOMONEY_PAYMENT_METHOD_NAME) }}</textarea>
                        </div>

                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://yookassa.ru">Yoomoney</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="{{ YOOMONEY_PAYMENT_METHOD_NAME }}_shop_id">{{ __('Shop ID') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_shop_id" id="{{ YOOMONEY_PAYMENT_METHOD_NAME }}_shop_id"
                                   value="{{ get_payment_setting('shop_id', YOOMONEY_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="{{ YOOMONEY_PAYMENT_METHOD_NAME }}_api_secret">{{ __('API Secret') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ YOOMONEY_PAYMENT_METHOD_NAME }}_api_secret" id="{{ YOOMONEY_PAYMENT_METHOD_NAME }}_api_secret"
                                   value="{{ get_payment_setting('api_secret', YOOMONEY_PAYMENT_METHOD_NAME) }}">
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, YOOMONEY_PAYMENT_METHOD_NAME) !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($yoomoneyStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($yoomoneyStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($yoomoneyStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
