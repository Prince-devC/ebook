@extends('admin.layout')

@section('title', 'Configuration Paiements')
@section('header', 'Configuration des Paiements')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.payment-settings.update') }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf

        <!-- Provider Selection -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-4">Provider de Paiement Actif</label>
            <div class="grid grid-cols-3 gap-4">
                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                    <input type="radio" name="payment_provider" value="paydunya" 
                           {{ \App\Models\PaymentSetting::get('payment_provider') === 'paydunya' ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600">
                    <span class="ml-3 font-medium">Paydunya</span>
                </label>
                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                    <input type="radio" name="payment_provider" value="fedapay"
                           {{ \App\Models\PaymentSetting::get('payment_provider') === 'fedapay' ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600">
                    <span class="ml-3 font-medium">FedaPay</span>
                </label>
                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                    <input type="radio" name="payment_provider" value="kkiapay"
                           {{ \App\Models\PaymentSetting::get('payment_provider') === 'kkiapay' ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600">
                    <span class="ml-3 font-medium">Kkiapay</span>
                </label>
            </div>
        </div>

        <!-- Paydunya Settings -->
        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Configuration Paydunya</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Master Key</label>
                    <input type="text" name="paydunya_master_key" value="{{ \App\Models\PaymentSetting::get('paydunya_master_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Private Key</label>
                    <input type="text" name="paydunya_private_key" value="{{ \App\Models\PaymentSetting::get('paydunya_private_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Token</label>
                    <input type="text" name="paydunya_token" value="{{ \App\Models\PaymentSetting::get('paydunya_token') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                    <select name="paydunya_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="test" {{ \App\Models\PaymentSetting::get('paydunya_mode') === 'test' ? 'selected' : '' }}>Test</option>
                        <option value="live" {{ \App\Models\PaymentSetting::get('paydunya_mode') === 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- FedaPay Settings -->
        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Configuration FedaPay</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Public Key</label>
                    <input type="text" name="fedapay_public_key" value="{{ \App\Models\PaymentSetting::get('fedapay_public_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secret Key</label>
                    <input type="text" name="fedapay_secret_key" value="{{ \App\Models\PaymentSetting::get('fedapay_secret_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                    <select name="fedapay_mode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="test" {{ \App\Models\PaymentSetting::get('fedapay_mode') === 'test' ? 'selected' : '' }}>Test</option>
                        <option value="live" {{ \App\Models\PaymentSetting::get('fedapay_mode') === 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Kkiapay Settings -->
        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Configuration Kkiapay</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Public Key</label>
                    <input type="text" name="kkiapay_public_key" value="{{ \App\Models\PaymentSetting::get('kkiapay_public_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Private Key</label>
                    <input type="text" name="kkiapay_private_key" value="{{ \App\Models\PaymentSetting::get('kkiapay_private_key') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secret</label>
                    <input type="text" name="kkiapay_secret" value="{{ \App\Models\PaymentSetting::get('kkiapay_secret') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                    <select name="kkiapay_sandbox" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="true" {{ \App\Models\PaymentSetting::get('kkiapay_sandbox') === 'true' ? 'selected' : '' }}>Sandbox</option>
                        <option value="false" {{ \App\Models\PaymentSetting::get('kkiapay_sandbox') === 'false' ? 'selected' : '' }}>Production</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                <i class="fas fa-save mr-2"></i>Enregistrer la Configuration
            </button>
        </div>
    </form>
</div>
@endsection
