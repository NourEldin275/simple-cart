<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    /**
     * @Route("/cart/", name="cart_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $cart_manager = $this->get('app.cart_manager');

        $cart_items = $cart_manager->getCartItems();
        $cart_subtotal = $cart_manager->getCartSubtotal();


        return $this->render('shop/cart.html.twig', array(
            'subtotal' => $cart_subtotal,
            'items' => $cart_items,
        ));
    }


    /**
     * @Route("/add-to-cart/{product}/{quantity}/", name="add_to_cart")
     * @param Product $product
     * @param $quantity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCartAction(Product $product, $quantity = 1){

        if (!$product){
            throw $this->createNotFoundException('Could not add to cart. Product not found.');
        }


        $cart_manager = $this->get('app.cart_manager');

        $cart_manager->addItem($product,$quantity);
        $this->addFlash('notice', $product->getProductName() .' is added to cart.');

        return $this->redirectToRoute('cart_page');
    }


    /**
     * @Route("/remove-cart-item/{product}/", name="remove_cart_item")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemAction(Product $product){

        if (!$product){
            throw $this->createNotFoundException('Could not remove item from cart. Product not found');
        }

        $cart_manager= $this->get('app.cart_manager');

        $cart_manager->removeItem($product);

        $this->addFlash('notice', 'Item removed');

        return $this->redirectToRoute('cart_page');

    }

    /**
     * @Route("/clear-cart/", name="clear_cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearCartAction(){

        $this->get('app.cart_manager')->clearCart();

        $this->addFlash('notice', 'Cart cleared.');

        return $this->redirectToRoute('shop');
    }
}
