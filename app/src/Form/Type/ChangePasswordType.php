<?php
/**
 * ChangePasswordType.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
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
        $builder->add(
            'oldPassword',
            PasswordType::class,
            [
                'label' => 'label.oldPassword',
                'required' => true,
                'attr' => ['max_length' => 255],
                'mapped' => false,
            ],
        );
        $builder->add(
            'newPassword',
            PasswordType::class,
            [
                'label' => 'label.newPassword',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'confirmPassword',
            PasswordType::class,
            [
                'label' => 'label.newPassword',
                'required' => true,
                'attr' => ['max_length' => 255],
                'mapped' => false,
            ],
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
