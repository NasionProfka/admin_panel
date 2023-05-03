<?php

namespace App\Tests\Entity;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testGetId()
    {
        // Test when the ID is not set
        $this->assertNull($this->user->getId());

        // Test when the ID is set
        $id = 1;
        $reflectionProperty = new ReflectionProperty(User::class, 'id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->user, $id);

        $this->assertEquals($id, $this->user->getId());
    }

    public function testGetName()
    {
        // Test when the name is not set
        $this->assertNull($this->user->getName());

        // Test when the name is set
        $name = 'Test User';
        $reflectionProperty = new ReflectionProperty(User::class, 'name');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->user, $name);

        $this->assertEquals($name, $this->user->getName());
    }

    public function testSetName()
    {
        $name = 'New User Name';
        $this->assertInstanceOf(User::class, $this->user->setName($name));
        $this->assertEquals($name, $this->user->getName());
    }

    public function testGetGroups()
    {
        $groups = new ArrayCollection([new Group(), new Group()]);
        $reflectionProperty = new ReflectionProperty(User::class, 'groups');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->user, $groups);
        
        $this->assertEquals($groups, $this->user->getGroups());
    }

    public function testAddGroup()
    {
        $group = new Group();
        $this->assertInstanceOf(User::class, $this->user->addGroup($group));
        $this->assertTrue($this->user->getGroups()->contains($group));
    }

    public function testRemoveGroup()
    {
        $group1 = new Group();
        $group2 = new Group();

        $this->user->addGroup($group1);
        $this->user->addGroup($group2);

        $this->assertInstanceOf(User::class, $this->user->removeGroup($group1));
        $this->assertFalse($this->user->getGroups()->contains($group1));
        $this->assertTrue($this->user->getGroups()->contains($group2));
    }
}
