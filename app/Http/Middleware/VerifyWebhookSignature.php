<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $provider = null): Response
    {
        // Vérifier la signature du webhook en fonction du fournisseur
        if ($provider === 'stripe') {
            $this->verifyStripeSignature($request);
        }  elseif ($provider === 'paypal') {
            $this->verifyPaypalSignature($request);
        } elseif ($provider === 'mailgun') {
            $this->verifyMailgunSignature($request);
        } elseif ($provider === 'sendgrid') {
            $this->verifySendgridSignature($request);
        } elseif ($provider === 'twilio') {
            $this->verifyTwilioSignature($request);
        }

        return $next($request);
    }

    /**
     * Verify Stripe webhook signature.
     */
    protected function verifyStripeSignature(Request $request): void
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            abort(403, 'Invalid signature');
        }
    }

    /**
     * Verify PayPal webhook signature.
     */
    protected function verifyPaypalSignature(Request $request): void
    {
        // Implémentation de la vérification de signature PayPal
        // À compléter selon la documentation PayPal
    }

    /**
     * Verify Mailgun webhook signature.
     */
    protected function verifyMailgunSignature(Request $request): void
    {
        // Implémentation de la vérification de signature Mailgun
        // À compléter selon la documentation Mailgun
    }

    /**
     * Verify SendGrid webhook signature.
     */
    protected function verifySendgridSignature(Request $request): void
    {
        // Implémentation de la vérification de signature SendGrid
        // À compléter selon la documentation SendGrid
    }

    /**
     * Verify Twilio webhook signature.
     */
    protected function verifyTwilioSignature(Request $request): void
    {
        // Implémentation de la vérification de signature Twilio
        // À compléter selon la documentation Twilio
    }
}