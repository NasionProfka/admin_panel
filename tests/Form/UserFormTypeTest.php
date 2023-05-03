<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Test User',
        ];

        $user = new User();

        $form = $this->factory->create(UserFormType::class, $user);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
