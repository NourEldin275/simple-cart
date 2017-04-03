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
        $cart_manager = $this->get('app.cart_manager');

        $session_cart_items = $cart_manager->getCartItems();
        $session_cart_subtotal = $cart_manager->getCartSubtotal();

        return $this->render('shop/cart.html.twig', array(
            'subtotal' => $session_cart_subtotal,
            'items' => $session_cart_items,
        ));
    }

    /**
     * @param Request $request
     * @Route("/edit-cart/", name="edit_cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCartAction(Request $request){

        $cart_manager = $this->get('app.cart_manager');

        $session_cart_items = $cart_manager->getCartItems();
        $session_cart_subtotal = $cart_manager->getCartSubtotal();


        /**
         * @var $cart Cart Holds an object to represent the cart.
         */
        $cart = new Cart();
        $cart->setSubtotal($session_cart_subtotal);

        // Creating AppBundle\Entity\CartItem objects and assigning their values from cart session. Then adding the item
        // to AppBundle\Entity\Cart object.

        foreach($session_cart_items as $cart_item){
            $item = new CartItem($cart_item['product']);
            $item->setQuantity($cart_item['quantity']);

            $cart->addItem($item);
        }

        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){

            // Updating $session_cart_items with the edited items.
            foreach( $cart->getItems() as $item ){

                // if use inputs a value less than 1, show flash message to guide them how to remove items.
                if ( $item->getQuantity() < 1){

                    // In case we want to remove the item if the quantity is set less than 1.
                    //unset($session_cart_items[$item->getProduct()->getId()]);
                    $this->addFlash('warning', 'If you wish to remove this item: '.$item->getItemName().' click the remove button in cart.');
                    continue;
                }
                // if quantity is more than available stock
                else if ($item->getQuantity() > $item->getProduct()->getItemsInStock()){
                    $this->addFlash('warning', 'Only '.$item->getProduct()->getItemsInStock().' items of '.$item->getItemName().' are left.');
                    continue;
                }


                $session_cart_items[$item->getProduct()->getId()]['quantity'] = $item->getQuantity();
                $session_cart_items[$item->getProduct()->getId()]['item_total'] = $item->getItemTotal();
            }

            $cart_manager->updateCartItems($session_cart_items);
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
