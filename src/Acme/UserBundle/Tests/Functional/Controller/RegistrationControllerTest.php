<?php

namespace Acme\UserBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PUGX\MultiUserBundle\Model\UserDiscriminator;

/**
 * 
*
* @author leonardo proietti <leonardo@netmeans.net>
*/
class RegistrationControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->initEntityManager();
    }
        
    public function userRegistrationProvider()
    {
        return array(
            array(
              "/register/user-one",
              "newuserone",
              "userone",              
              "newuserone@netmeans.net",
              "UserOne",
            ),
            array(
              "/register/user-two",
              "newusertwo",
              "usertwo",
              "newusertwo@netmeans.net",
              "UserTwo",
            )
        );
    } 
    
    public function userConfirmProvider()
    {
        return array(
            array(           
              "useronenotconfirmed@netmeans.net",
              "UserOne",
              "abcdefg"
            ),
            array(
              "usertwonotconfirmed@netmeans.net",
              "UserTwo",
              "pkdddfff"
            )
        );
    } 
    
    /**
     * @dataProvider userRegistrationProvider
     */
    public function testRegistration($path, $username, $password, $email, $entity)
    {
        $client     = static::createClient();
        $client->followRedirects(true);
                
        $crawler = $client->request('GET', $path);
        
        $client->followRedirects(false);
        
        $form = $crawler->selectButton('Register')->form();
        
        $crawler = $client->submit(
            $form,
            array(
                'fos_user_registration_form[username]'                  => $username,
                'fos_user_registration_form[email]'                     => $email,
                'fos_user_registration_form[plainPassword][first]'      => $password,
                'fos_user_registration_form[plainPassword][second]'     => $password,
            )
        );
        
        $mailCollector = $this->getMailCollector($client);
        $this->assertEquals(1, $mailCollector->getMessageCount());
        
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        $user = $this->em->getRepository('AcmeUserBundle:'.$entity)->findOneByEmail($email);          
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals('Acme\UserBundle\Entity\\' . $entity, get_class($user));
    }
    
    /**
     * @dataProvider userRegistrationProvider
     */
    public function testRegistrationValidation($path, $username, $password, $email, $entity)
    {
        $client     = static::createClient();
        $client->followRedirects(true);
                
        $crawler    = $client->request('GET', $path);
        
        $form = $crawler->selectButton('Register')->form();
        
        $crawler = $client->submit(
            $form,
            array(
                'fos_user_registration_form[username]'                  => "",
                'fos_user_registration_form[email]'                     => "",
                'fos_user_registration_form[plainPassword][first]'      => "",
                'fos_user_registration_form[plainPassword][second]'     => "",
            )
        );
        
        $this->assertRegExp("/Please enter a username/", $crawler->filter("ul li")->eq(0)->text());
    }
    
    /**
     * @dataProvider userConfirmProvider
     */
    public function testConfirmRegistration($email, $entity, $token)
    {
        $client     = static::createClient();
        $client->followRedirects(true);
        
        $crawler    = $client->request('GET', '/register/confirm/' . $token);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertRegExp("/your account is now activated/", $crawler->filter("body")->text());
        
        $session = $client->getContainer()->get("session");
        $this->assertEquals('Acme\UserBundle\Entity\\' . $entity, $session->get(UserDiscriminator::SESSION_NAME)); 
    }
    
    public function uniqueValidationProvider()
    {
        return array(
            array(
              "/register/user-one",
              "userone",
              "userone",              
              "newuserone@netmeans.net"
            ),
            array(
              "/register/user-two",
              "userone",
              "userone",
              "newuserone@netmeans.net"
            )
        );
    }
    
    /**
     * @dataProvider uniqueValidationProvider
     */
    public function testUniqueValidation($path, $username, $password, $email)
    {
        $client = static::createClient();
        $client->followRedirects(true);
                
        $crawler = $client->request('GET', $path);
        
        $form = $crawler->selectButton('Register')->form();
        
        $crawler = $client->submit(
            $form,
            array(
                'fos_user_registration_form[username]'                  => $username,
                'fos_user_registration_form[email]'                     => $email,
                'fos_user_registration_form[plainPassword][first]'      => $password,
                'fos_user_registration_form[plainPassword][second]'     => $password,
            )
        );
        
        $this->assertRegExp("/The username is already used/", $crawler->filter("ul li")->eq(0)->text());
    }
    
    public function tearDown()
    {
        $userOne = $this->em->getRepository('AcmeUserBundle:UserOne')->findOneByEmail('useronenotconfirmed@netmeans.net');        
        $userOne->setEnabled(false);
        $userOne->setConfirmationToken('abcdefg');
        $this->em->persist($userOne);
        
        $userTwo = $this->em->getRepository('AcmeUserBundle:UserTwo')->findOneByEmail('usertwonotconfirmed@netmeans.net');        
        $userTwo->setEnabled(false);
        $userTwo->setConfirmationToken('pkdddfff');
        $this->em->persist($userTwo);
        
        $userNewOne = $this->em->getRepository('AcmeUserBundle:UserOne')->findOneByEmail('newuserone@netmeans.net');   
        if ( $userNewOne ) {
            $this->em->remove($userNewOne);
        }       
        
        $userNewTwo = $this->em->getRepository('AcmeUserBundle:UserTwo')->findOneByEmail('newusertwo@netmeans.net');   
        if ( $userNewTwo ) {
            $this->em->remove($userNewTwo);
        }
        
        $this->em->flush();
    }
    
    
}