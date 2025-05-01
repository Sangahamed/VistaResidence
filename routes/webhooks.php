<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhooks\PaymentWebhookController;
use App\Http\Controllers\Webhooks\EmailWebhookController;
use App\Http\Controllers\Webhooks\SmsWebhookController;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
| Here is where you can register webhook routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "webhook" middleware group.
|
*/

// Webhooks pour les paiements
Route::post('/payments/stripe', [PaymentWebhookController::class, 'handleStripe'])
    ->middleware('webhook.verify:stripe');
    
Route::post('/payments/paypal', [PaymentWebhookController::class, 'handlePaypal'])
    ->middleware('webhook.verify:paypal');

// Webhooks pour les emails
Route::post('/emails/mailgun', [EmailWebhookController::class, 'handleMailgun'])
    ->middleware('webhook.verify:mailgun');
    
Route::post('/emails/sendgrid', [EmailWebhookController::class, 'handleSendgrid'])
    ->middleware('webhook.verify:sendgrid');

// Webhooks pour les SMS
Route::post('/emails/twilio', [SmsWebhookController::class, 'handleTwilio'])
    ->middleware('webhook.verify:twilio');