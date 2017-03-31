<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{

    /**
     * @Route("/add-new-product/", name="add_new_product")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('notice', 'Product ' .$product->getProductName() .' is added successfully.');
            return $this->redirectToRoute('list_products');
        }

        return $this->render('product/add-edit-product.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/products/", name="list_products")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){

        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();

        return $this->render('product/list-products.html.twig', array('products' => $products));
    }


    /**
     * @Route("/edit-product/{product}/", name="edit_product")
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product){

        if ( !$product ){
            throw $this->createNotFoundException('Product Not Found!');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('notice', 'Product '. $product->getProductName() .' saved successfully.');
            return $this->redirectToRoute('list_products');
        }


        return $this->render('product/add-edit-product.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete-product/{product}/", name="delete_product")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Product $product){
        if ( !$product){
            throw $this->createNotFoundException('Product Not Found!');
        }

        $product_name = $product->getProductName();

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('notice', 'Product: ' . $product_name . ' is deleted.');

        return $this->redirectToRoute('list_products');
    }
}
