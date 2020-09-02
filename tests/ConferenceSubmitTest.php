<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceSubmitTest extends WebTestCase {

    public function testCommentSubmission()
    {
        $client = static::createClient();
        $response = $client->request('GET', '/conference/amsterdam-2019');

        $client->submitForm('Submit', [
            'comment_form[author]' => 'Ivan',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'me@automat.ed',
            'comment_form[photo]' => dirname(__DIR__, 1).'/public/images/under-construction.gif',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 1 comments")');
    }
}