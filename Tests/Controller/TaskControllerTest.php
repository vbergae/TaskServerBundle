<?php

namespace VBcom\TaskServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
  
    public function testIndex()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        $this->assertTrue(true);
    }
}
