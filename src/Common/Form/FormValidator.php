<?php

namespace App\Common\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];
        if(!$form->isSubmitted()){
            $form->submit([]);
        }
        if (!$form->isValid()) {
            foreach ($form->getErrors(true, false) as $e){
                $errors[$e->getForm()->getName()] = $e->getChildren()->getMessage();
            }
        }

        return $errors;
    }
}