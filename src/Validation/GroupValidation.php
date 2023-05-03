<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupValidation
{
    private $validator;

    public function __construct (ValidatorInterface $validator) {
        $this->validator = $validator;
    } 

    public function validate(array $input_fields): array
    {
        $errors = [];
        $rules = $this->createGroupValidationRules();

        foreach ($input_fields as $key => $input) {
            $violations = $this->validator->validate($input, $rules[$key]);

            if (count($violations) > 0) {            
                foreach ($violations as $violation) {
                    $errors[$key] = $violation->getMessage();
                }
    
            }
        }

        return $errors;
    }

    private function createGroupValidationRules(): array
    {
        return [
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3]),
            ],
        ];
    }
}