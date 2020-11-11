<?php
namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    public function listProducts(): Response
    {
        $products = $this->getDoctrine()
          ->getRepository(Product::class)
          ->findAll();

        return $this->render('store/products-grid.html.twig', [
            'products' => $products,
        ]);
    }
    
    public function checkoutPayments($id)
    {   
        $secretKey = 'sk_test_51HlrWAFt9Eh6gA3y4dxI7RJrTBwaWZXGleJ6VHvmWhlNa8QetsCC2Hg11XbNUfTzZ9jEwed8rf2m3Hg5rDkJq6Zq00uymD7BB9';
        $publishableKey = 'pk_test_51HlrWAFt9Eh6gA3y1AhlYARF4oX8f78wobHaqIWgL2CUZlGax7f2fbwvl2J3CZ9Yj3FjMiGoo42ZpbO5C0884Ahq00jGNCffmT';
         \Stripe\Stripe::setApiKey($secretKey);
        		
		    $product = $this->getDoctrine()
          ->getRepository(Product::class)
          ->find($id);
        $price = $product->getPrice();
        $price *= 100;
        
        $payment_intent = \Stripe\PaymentIntent::create([
			    'description' => 'Stripe Test Payment',
			    'amount' => $price,
			    'currency' => 'INR',
			    'description' => 'Payment From Symstore',
			    'payment_method_types' => ['card'],
		    ]);
		    $intent = $payment_intent->client_secret;

		    return $this->render('store/payments-checkout.html.twig', [
		      'pubKey' => $publishableKey,
 		      'intent' => $intent,
 		      'price' => $price,
		    ]);

    }

    public function successMessage()
    {
        return new Response(
            '<html><body><h1>Thank you, payment has been received.</h1></body></html>'
        );
    }
}
