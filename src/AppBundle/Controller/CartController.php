<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;
use AppBundle\Form\CartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * @Route("/cart/", name="cart_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find(1);
        $cart_items = $cart->getItems();

        return $this->render('shop/cart.html.twig', array(
            'subtotal' => $cart->getSubtotal(),
            'items' => $cart_items,
        ));
    }

    /**
     * @param Request $request
     * @Route("/edit-cart/", name="edit_cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCartAction(Request $request){

        $cart_manager = $this->get('app.cart_manager');


        /**
         * @var $cart Cart Holds an object to represent the cart.
         */
        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find(1);

        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){

            // Updating $session_cart_items with the edited items.
            foreach( $cart->getItems() as $item ){

                // if user inputs a value less than 1, show flash message to guide them how to remove items.
                if ( $item->getQuantity() < 1){

                    // We cam also remove the item instead of setting its quantity to 1
                    $item->setQuantity(1);
                    $this->addFlash('warning', 'If you wish to remove this item: '.$item->getItemName().' click the remove button in cart.');
                }
                // if quantity is more than available stock
                else if ($item->getQuantity() > $item->getProduct()->getItemsInStock()){
                    $item->setQuantity($item->getProduct()->getItemsInStock());
                    $this->addFlash('warning', 'Only '.$item->getProduct()->getItemsInStock().' items of '.$item->getItemName().' are left.');
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            $cart_manager->updateCartItems();
            $this->addFlash('notice', 'Cart Updated');
            return $this->redirectToRoute('cart_page');
        }

        return $this->render('shop/edit-cart-items.html.twig', array(
            'form' => $form->createView(),
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
        else if ( $product->getItemsInStock() < $quantity ){
            $this->addFlash('notice', 'Only '.$product->getItemsInStock().' is left in stock for this item '. $product->getProductName());
            return $this->redirectToRoute('shop');
        }


        $cart_manager = $this->get('app.cart_manager');

        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find(1);

        $item = $this->getDoctrine()->getRepository('AppBundle:CartItem')->findOneBy(array(
            'cart' => $cart,
            'product' => $product,
        ));

        // The it is not in the cart.
        if ( !$item ){
            $item = new CartItem($product, $cart);
        }

        $cart_manager->addItem($item,$quantity);

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

        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find(1);

        $item = $this->getDoctrine()->getRepository('AppBundle:CartItem')->findOneBy(array(
            'cart' => $cart,
            'product' => $product,
        ));

        $cart_manager->removeItem($item);

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
