<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testPasswordVerificationWorks()
    {
        $user = new User(
            Uuid::uuid4(),
            'random@email.com',
            'NotAVerySecureTestPassword',
            'FirstName',
            'LastName'
        );

        $this->assertTrue($user->verifyPassword('NotAVerySecureTestPassword'));
    }
}
