<?php

namespace App\Tests\Form;

use App\Entity\Group;
use App\Form\GroupFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class GroupFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Test Group',
        ];

        $group = new Group();

        $form = $this->factory->create(GroupFormType::class, $group);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($group, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
