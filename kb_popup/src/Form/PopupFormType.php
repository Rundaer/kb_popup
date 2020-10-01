<?php

namespace PrestaShop\Module\Kb_Popup\Form;

use PrestaShop\Module\Kb_Popup\Entity\Popup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\TranslateType;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Product;
use Category;
use Language;

class PopupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $disabledCategories = [
            2, // category id
        ];

        $builder
            ->add('isImage', CheckboxType::class, [
                'label'    => 'Will it be only image?',
                'required' => false,
            ])
            ->add('page_select', ChoiceType::class, [
                'choices'  => [
                    'product page' => 'product',
                    'category page' => 'category',
                ],
            ])
            ->add('id_category', CategoryChoiceTreeType::class, [
                'disabled_values' => $disabledCategories,
            ])
            ->add('id_product', ChoiceType::class, [
                'choices'  => $this->showProduct(),
            ])
            ->add('text', FormattedTextareaType::class, [
                    'label' => 'test',
                    'required' => false
            ])
            ->add('link', TextType::class, ["label" => "Link"])
            ->add('image', FileType::class, [
                "label" => "Image",
                "mapped" => false,
                "required" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('backgroundColor', TextType::class, [
            ])
            ->add('startsAt', DatePickerType::class, [
                'label' => 'Select a value when popup should start',
            ])
            ->add('endsAt', DatePickerType::class, [
                'label' => 'Select a value when popup should end',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Send']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Popup::class,
        ]);
    }

    private function showProduct()
    {
        // TODO:lang id
        $products = Product::getProducts(1,1,9999,'name','ASC');
        $choices = [];
        foreach ($products as $product){
            $choices[$product['id_product'].'-'.$product['name']] = $product['id_product'];
        }

        return $choices;
    }
}
