<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testAjoutpko()
    {
        
            $client = static::createClient([],[
                'PHP_AUTH_USER' => 'kyadiop',
                'PHP_AUTH_PW' => 'kyadiop97',
            ]
            );
            $client->request('POST', '/api/ajoutp',[],[],['CONTENT_TYPE'=>"application/json"],
            '{
                "rs":"multitransfert",
                "ninea":"55353",
                "adresse":"dakar",
                "username":"kya001",
                "password":"0000"
                
                }'
        );
            $rep=$client->getResponse();
            // var_dump($rep);
            $this->assertSame(401,$client->getResponse()->getStatusCode());
            //$this->assertJsonStringEqualsJsonString($jsonstring,$rep->getContent());
    }

    public function testSuperadminko()
    {
        
        
            $client = static::createClient([],[
                'PHP_AUTH_USER' => 'kyadiop',
                'PHP_AUTH_PW' => 'kyadiop97',
            ]
            );
            $client->request('POST', '/api/caissier',[],[],['CONTENT_TYPE'=>"application/json"],
            '{
                "username":"Bousso",
                "password":"261297",
                "nomcomplet":"jonijoni",
                "mail":"bousso@gmail.com",
                "tel":775556222,
                "adresse":"Parcelle",
                "idpartenaire":"",
                "compte":""
                }'
        );
            $rep=$client->getResponse();
            // var_dump($rep);
            $this->assertSame(401,$client->getResponse()->getStatusCode());
            //$this->assertJsonStringEqualsJsonString($jsonstring,$rep->getContent());
    }


    public function testRegisterko()
    {
        
        
            $client = static::createClient([],[
                'PHP_AUTH_USER' => 'yonema00',
                'PHP_AUTH_PW' => '02612',
            ]
            );
            $client->request('POST', '/api/register',[],[],['CONTENT_TYPE'=>"application/json"],
            '{
                "username":"eva",
                "roles":"[ROLE_ADMIN]",
                "password":"261997",
                "nomcomplet":"awa fall",
                "mail":"eva@gmail.com",
                "tel":"771565879",
                "adresse":"Parcelle",
                "idpartenaire":"1",
                "compte":"2"
                }'
        );
            $rep=$client->getResponse();
            // var_dump($rep);
            $this->assertSame(401,$client->getResponse()->getStatusCode());
            //$this->assertJsonStringEqualsJsonString($jsonstring,$rep->getContent());
    }
}
