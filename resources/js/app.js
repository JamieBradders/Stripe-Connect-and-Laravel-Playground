import './bootstrap';

// Stripe Example Code
// Use this to load a stripe embedded component
import {loadConnectAndInitialize} from '@stripe/connect-js';

const fetchClientSecret = async () => {
    // Fetch the AccountSession client secret
    const response = await fetch('/api/stripe/account-session', { method: "POST", body: JSON.stringify({
            stripe_account_id: "acct_1Q4JyZCYLYCd9GMg" // Replace later on...
        }) });

    if (!response.ok) {
        // Handle errors on the client side here
        const {error} = await response.json();
        console.error('An error occurred: ', error);
        document.querySelector('#error').removeAttribute('hidden');
        return undefined;
    } else {
        const {client_secret: clientSecret} = await response.json();
        document.querySelector('#error').setAttribute('hidden', '');
        return clientSecret;
    }
}

const container = document.getElementById("container");

if (container) {
    const stripeConnectInstance = loadConnectAndInitialize({
        // This is a placeholder - it should be replaced with your publishable API key.
        // Sign in to see your own test API key embedded in code samples.
        // Don’t submit any personally identifiable information in requests made with this key.
        // publishableKey: "pk_test_qblFNYngBkEdjEZ16jxxoWSM",
        publishableKey: "pk_test_51LqIQeClCSwUfxEMnzwm6NNJWQQTdE8VPRp3VhHZlFLH33k3b5jYD9wSyU225iVAlezv0DV19CpAgSKglot1QLX400L1co7rE3",
        fetchClientSecret: fetchClientSecret,
    });

    const paymentComponent = stripeConnectInstance.create("payouts");
    container.appendChild(paymentComponent);
}
