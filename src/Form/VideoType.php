<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File(
                        [
                            'mimeTypes' => [
                                'video/mp4',
                                'video/mpeg',
                                'video/m4v',
                                'video/mov'
                            ],
                            'mimeTypesMessage' => "Seules les vidéos .mp4, .mpeg, .m4v et .mov sont acceptées.",
                        ]
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
