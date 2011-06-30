<?php

namespace VBcom\TaskServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('start_date')
            ->add('finish_date')
            ->add('done')
            ->add('priority')
        ;
    }
}