<?php

namespace VBcom\TaskServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
  
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/server/task/');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'task[title]'                   => 'Test',
            'task[description]'             => 'Some description',
            'task[start_date][date][year]'  => '2011',
            'task[start_date][date][month]' => '07',
            'task[start_date][date][day]'   => '24',
            'task[start_date][time][hour]'  => '01',
            'task[start_date][time][minute]'=> '30',
            'task[finish_date][date][year]'  => '2011',
            'task[finish_date][date][month]' => '07',
            'task[finish_date][date][day]'   => '23',
            'task[finish_date][time][hour]'  => '01',
            'task[finish_date][time][minute]'=> '30',
            'task[done]'                     => '1',
            'task[priority]'                 => '0'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertTrue($crawler->filter('td:contains("Test")')->count() > 0);

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form(array(
            'task[title]'                   => 'Testaaaa',
            'task[description]'             => 'Some description',
            'task[start_date][date][year]'  => '2011',
            'task[start_date][date][month]' => '07',
            'task[start_date][date][day]'   => '24',
            'task[start_date][time][hour]'  => '01',
            'task[start_date][time][minute]'=> '30',
            'task[finish_date][date][year]'  => '2011',
            'task[finish_date][date][month]' => '07',
            'task[finish_date][date][day]'   => '23',
            'task[finish_date][time][hour]'  => '01',
            'task[finish_date][time][minute]'=> '30',
            'task[done]'                     => '1',
            'task[priority]'                 => '4'
        ));
        
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('[value="edited"]')->count() > 0);

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}
