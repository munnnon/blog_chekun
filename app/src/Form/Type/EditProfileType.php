<?php

/**
 * EditProfileType.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditProfileType.
 */
class EditProfileType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
         * Builds the form.
         *
         * This method is called for each type in the hierarchy starting from the
         * top most type. Type extensions can further modify the form.
         *
         * @param FormBuilderInterface $builder The form builder
         * @param array<string, mixed> $options Form options
         *
         * @see FormTypeExtensionInterface::buildForm()
         */
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('nickname', TextType::class, [
                'label' => 'label.nickname',
            ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User',
        ]);
    }
}
