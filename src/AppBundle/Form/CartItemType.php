<?php

namespace AppBundle\Form;

use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class)
            ->add('itemName', TextType::class, array('disabled' => true))
            ->add('itemPrice', NumberType::class, array('disabled' => true))
            ->add('itemTotal', NumberType::class, array('disabled' => true));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CartItem::class,
        ));
    }

    public function getName()
    {
        return 'app_bundle_cart_item_type';
    }
}
