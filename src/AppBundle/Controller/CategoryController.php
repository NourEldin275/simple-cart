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
        }



        return $this->render('product-category/add-edit-category.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
