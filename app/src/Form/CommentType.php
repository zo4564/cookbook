<?php
/**
 * Comment type.
 */

namespace App\Form;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType.
 */
class CommentType extends AbstractType
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
            'content',
            TextType::class,
            [
                'label' => 'label.content',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        //        if ($options['current_user']) {
        //            $builder->add('user', EntityType::class, [
        //                'class' => User::class,
        //                'label' => 'label.user',
        //                'required' => true,
        //                'disabled' => true,
        //                'choice_label' => 'username',
        //                'data' => $options['current_user'], // Set the 'current_user' option as the default value for the user field
        //            ]);
        //        }
        //        if ($options['current_recipe']) {
        //            $builder->add('recipe', EntityType::class, [
        //                'class' => Recipe::class,
        //                'label' => 'label.recipe',
        //                'required' => true,
        //                'disabled' => true,
        //                'choice_label' => 'title',
        //                'data' => $options['current_recipe'],
        //            ]);
        //        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Comment::class,
            'current_user' => null, // Add the 'current_user' option with a default value of null
            'current_recipe' => null,
        ]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'comment';
    }
}
