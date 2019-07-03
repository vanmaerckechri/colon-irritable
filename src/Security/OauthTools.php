<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OauthTools
{
    private $em;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function isUserHaveAccount($socialUser)
    {
        $email = $socialUser->getEmail();
        $user = $this->em->getRepository('App:User')
            ->findOneBy(['mail' => $email]);

        return $user;
    }

    public function recordNewUser($socialUser)
    {
        // temporary name to record in db
        $socialUserName = $socialUser->getName();
        $newName = $socialUserName;
        // if name already exist in db => auto update new user name
        $isUserWithThisName = $this->em->getRepository('App:User')
            ->findOneBy(['username' => $socialUserName]);
        if ($isUserWithThisName)
        {
            $index = 1;
            $newName = $socialUserName . '-' . $index;
            while($this->em->getRepository('App:User')
                ->findOneBy(['username' => $newName]))
            {
                $index += 1;
                $newName = $socialUserName . '-' . $index;
            }
        }

        $user = new User();
        $user = $password = $this->generatePassword($user);
        $user->setIsActive(1);
        $user->setMail($socialUser->getEmail());
        $user->setUsername($newName);
        $user->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function generatePassword(User $user)
    {
        $newWord = "";
        $newWordlength = 12;
        $caracList = "azertyuiopqsd&é(§è!çà)-fghjklmwxcvbn012345_6789&é(§è!çà)-AZERTYUIOPQSD&é(§è!çà)-FGHJKLMWXCVBN?.+";
        $caracListLength = strlen($caracList) - 1;
        for ($i = $newWordlength - 1; $i >= 0; $i--)
        {
            $rand = random_int(0, $caracListLength);
            $newWord .= $caracList[$rand];
        }

        $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $newWord
                )
            );

        return $user;
    }
}