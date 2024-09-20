<?php

namespace App\Listeners;

use App\User;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

class StripeSubscriptionCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        if ($event->payload['type'] === 'customer.subscription.created') {

            $object = $event->payload['data']['object'];
            // convert $object to an object

            $user = User::where('email', $object['customer_email'])->first();

            if ($user) {
                $user->stripe_id = $object['customer'];
                $subscription = Subscription::where('stripe_id', $object['id'])->first();
                if (! $subscription) {
                    // Create new subscription
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'stripe_id' => $object['id'],
                        'stripe_plan' => $object['plan']['id'],
                        'quantity' => $object['quantity'],
                        'trial_ends_at' => $object['trial_end'],
                        'ends_at' => $object['current_period_end'],
                    ]);
                    // Create new subscription item
                    SubscriptionItem::create([
                        'subscription_id' => $subscription->id,
                        'stripe_id' => $object['plan']['id'],
                        'stripe_product' => $object['plan']['product'],
                        'stripe_price' => $object['plan']['amount'],
                        'quantity' => $object['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                Log::error("No user found for email {$object->customer_email}");
            }
        }
    }
}
