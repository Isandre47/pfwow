<?php

namespace App\Controller;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/stripe/index", name="stripe_index")
     */
    public function index()
    {
        $intent = new PaymentIntent();
        $intent->amount = 1099;
        $intent->currency = 'eur';
        $intent->metadata = ['integration_check' => 'accept_a_payment'];
        $intent->client_secret = $_ENV['STRIPE_SECRET_KEY'];

        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
            'intent' => $intent,
        ]);
    }

    /**
     * @Route("/stripe/payment", name="stripe_payment")
     */
    public function payment(Request $request)
    {
        return $this->render('stripe/payment.html.twig', [
            'controller_name' => 'StripeController',
            'amount' => $request->request->get('montant'),
//            'amount' => 45,
        ]);
    }

    /**
     * @Route("/create-payment-intent", name="create_payment_intent")
     */
    public function createPaymentIntent()
    {
        $data = json_decode(file_get_contents('php://input'));
        Stripe::$apiKey = $_ENV['STRIPE_SECRET_KEY'];

        $paymentIntent = PaymentIntent::create([
           'amount' => $data->amount * 100,
           'currency' => $data->currency,
        ]);

        $output = [
            'publishableKey' => $_ENV['STRIPE_PUBLIC_KEY'],
            'clientSecret' => $paymentIntent->client_secret,
        ];

        return new JsonResponse($output, 200);
    }
}
