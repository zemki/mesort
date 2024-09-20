<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Laravel\Cashier\SubscriptionItem;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;

class SyncDataWithStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Subscription Data With Stripe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $count = 0;

        // Get all customers from Stripe
        $customers = Customer::all();
        // Loop through customers
        foreach ($customers->data as $customer) {
            $user = User::where('email', $customer->email)->first();
            if ($user) {
                // Retrieve subscriptions for the customer
                $subscriptions = Subscription::all(['customer' => $customer->id]);
                foreach ($subscriptions->data as $subscription) {

                    $subscription_item = SubscriptionItem::where('subscription_id', $subscription->id)->first();
                    if (! $subscription_item) {
                        // Create new subscription item
                        $subscription_item = SubscriptionItem::create([
                            'subscription_id' => $subscription->id,
                            'stripe_id' => $subscription->plan->id,
                            'stripe_product' => $subscription->plan->product,
                            'stripe_price' => $subscription->plan->amount,
                            'quantity' => $subscription->quantity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    // Add the subscription to the user
                    $user->subscriptions()->attach($subscription_item->id, [
                        'stripe_id' => $subscription->id,
                        'stripe_plan' => $subscription->plan->id,
                        'quantity' => $subscription->quantity,
                        'trial_ends_at' => $subscription->trial_end,
                        'ends_at' => $subscription->current_period_end,
                    ]);
                    $count++;
                }
            } else {
                $this->warn("No user found for email {$customer->email}");
            }
        }

        $this->info("Data synced successfully for {$count} user(s)!");
    }
}
