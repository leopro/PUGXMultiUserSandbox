<?php

namespace Acme\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Acme\UserBundle\Entity\UserOne;
use Acme\UserBundle\Entity\UserTwo;
use Acme\UserBundle\Entity\UserThree;

/**
 * Loads the user fixtures
 *
 * @author Leonardo Proietti
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $userOne = new UserOne();
        $userOne->setUsername('userone');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userOne);
        $userOne->setPassword($encoder->encodePassword('userone', $userOne->getSalt()));
        $userOne->setEmail('userone@netmeans.net');
        $userOne->setEnabled(true);        
        $manager->persist($userOne);  
        
        $userTwo = new UserTwo();
        $userTwo->setUsername('usertwo');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userTwo);
        $userTwo->setPassword($encoder->encodePassword('usertwo', $userTwo->getSalt()));
        $userTwo->setEmail('usertwo@netmeans.net');
        $userTwo->setEnabled(true);        
        $manager->persist($userTwo);  
        
        $userThree = new UserThree();
        $userThree->setUsername('userthree');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userThree);
        $userThree->setPassword($encoder->encodePassword('userthree', $userThree->getSalt()));
        $userThree->setEmail('userthree@netmeans.net');
        $userThree->setEnabled(true);        
        $manager->persist($userThree);
        
        $userOneNotConfirmed = new UserOne();
        $userOneNotConfirmed->setUsername('userone-not-confirmed');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userOneNotConfirmed);
        $userOneNotConfirmed->setPassword($encoder->encodePassword('userone-not-confirmed', $userOneNotConfirmed->getSalt()));
        $userOneNotConfirmed->setEmail('useronenotconfirmed@netmeans.net');
        $userOneNotConfirmed->setConfirmationToken('abcdefg');
        $userOneNotConfirmed->setEnabled(false);
        $manager->persist($userOneNotConfirmed);  
        
        $userTwoNotConfirmed = new UserTwo();
        $userTwoNotConfirmed->setUsername('usertwo-not-confirmed');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userTwoNotConfirmed);
        $userTwoNotConfirmed->setPassword($encoder->encodePassword('usertwo-not-confirmed', $userTwoNotConfirmed->getSalt()));
        $userTwoNotConfirmed->setEmail('usertwonotconfirmed@netmeans.net');
        $userTwoNotConfirmed->setConfirmationToken('pkdddfff');
        $userTwoNotConfirmed->setEnabled(false);
        $manager->persist($userTwoNotConfirmed); 
        
        $manager->flush();
        
        $this->addReference('user.one', $userOne);
        $this->addReference('user.two', $userTwo);    
    }
    
    public function getOrder()
    {
        return 1;
    }
}
