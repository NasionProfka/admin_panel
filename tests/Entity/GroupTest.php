<?php

namespace App\Tests\Entity;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class GroupTest extends TestCase
{
    private Group $group;

    protected function setUp(): void
    {
        $this->group = new Group();
    }

    public function testGetId()
    {
        // Test when the ID is not set
        $this->assertNull($this->group->getId());

        // Test when the ID is set
        $id = 1;
        $reflectionProperty = new ReflectionProperty(Group::class, 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->group, $id);

        $this->assertEquals($id, $this->group->getId());
    }

    public function testGetName()
    {
        $this->assertNull($this->group->getName());

        $name = 'Test Group';
        $reflectionProperty = new ReflectionProperty(Group::class, 'name');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->group, $name);

        $this->assertEquals($name, $this->group->getName());
    }

    public function testSetName()
    {
        $name = 'New Group Name';
        $this->assertInstanceOf(Group::class, $this->group->setName($name));
        $this->assertEquals($name, $this->group->getName());
    }

    public function testGetUsers()
    {
        $users = new ArrayCollection([new User(), new User()]);
        $reflectionProperty = new ReflectionProperty(Group::class, 'users');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->group, $users);
        
        $this->assertEquals($users, $this->group->getUsers());
    }
}
