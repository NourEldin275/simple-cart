<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use AppBundle\Form\ProductCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     *
     * @Route("/add-new-category/", name="add_new_category")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $product_category = new ProductCategory();

        $form = $this->createForm(ProductCategoryType::class, $product_category);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product_category);
            $em->flush();

            $this->addFlash('notice', 'Category ' . $product_category->getCategoryName(). ' added successfully');
            return $this->redirectToRoute('list_product_categories');
        }



        return $this->render('product-category/add-edit-category.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/product-categories/", name="list_product_categories")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){

        $product_categories = $this->getDoctrine()->getRepository('AppBundle:ProductCategory')->findAll();

        return $this->render('product-category/list-product-categories.html.twig', array(
            'product_categories' => $product_categories,
        ));
    }


    /**
     * @Route("/edit-product-category/{product_category}/", name="edit_product_category")
     * @param Request $request
     * @param ProductCategory $product_category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ProductCategory $product_category){

        if ( !$product_category ){
            throw $this->createNotFoundException('Product Category not found');
        }

        $form = $this->createForm(ProductCategoryType::class, $product_category);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product_category);
            $em->flush();

            $this->addFlash('notice', 'Product Category '. $product_category->getCategoryName() . ' saved successfully');
            return $this->redirectToRoute('list_product_categories');
        }

        return $this->render('product-category/add-edit-category.html.twig', array(
            'form' => $form->createView(),
            'product_category' => $product_category,
        ));
    }


    /**
     * @Route("/delte-product-category/{product_category}/", name="delete_product_category")
     * @param ProductCategory $product_category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(ProductCategory $product_category){

        if ( !$product_category ){
            throw $this->createNotFoundException('Product Category not found!');
        }

        $category_name = $product_category->getCategoryName();

        $em = $this->getDoctrine()->getManager();
        $em->remove($product_category);
        $em->flush();

        $this->addFlash('notice', 'Removed product category: ' . $category_name);
        return $this->redirectToRoute('list_product_categories');

    }
}
