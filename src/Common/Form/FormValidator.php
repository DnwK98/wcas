<?php

declare(strict_types=1);

namespace App\Common\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormValidator
{
    public static function validate(FormInterface $form): array
    {
        $errors = [];
        if (!$form->isSubmitted()) {
            $form->submit([]);
        }
        if (!$form->isValid()) {
            foreach ($form->getErrors(true, false) as $a => $e) {
                if ($e instanceof FormError) {
                    $errors['general'] = $e->getMessage();
                }
                $errors[$e->getForm()->getName()] = $e->getChildren()->getMessage();
            }
        }

        return $errors;
    }
}
