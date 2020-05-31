<?php

namespace App\Form\Character;

use App\Entity\Blizzard\Realm;
use App\Entity\Character\Profile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('blizzard_character_id')
            ->add('name')
//            ->add('gender')
//            ->add('faction')
//            ->add('race')
//            ->add('active_spec')
//            ->add('guild')
//            ->add('level')
//            ->add('achievement_points')
//            ->add('media')
//            ->add('last_login_timestamp')
//            ->add('equipped_item_level')
//            ->add('character_class')
            ->add('realm', EntityType::class, [
                'class'  =>  Realm::class,
                'choice_label'  =>  'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
