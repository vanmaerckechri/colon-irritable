<?php
namespace App\Security;

//use App\Exception\AccountDeletedException;
use App\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Exception\AccountIsNotActiveException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Security\SendAccountCode;


class UserChecker extends AbstractController implements UserCheckerInterface
{
    /**
     * @var SendAccountCode
     */
    private $sendAccountCode;

    public function __construct(SendAccountCode $sendAccountCode)
    {
        $this->sendAccountCode = $sendAccountCode;
    }

    public function checkPreAuth(UserInterface $user)
    {

    }

    public function checkPostAuth(UserInterface $user)
    {
        if ($user->getIsActive() == false)
        {
            $this->sendAccountCode->sendActivationLink($user);

            throw new AccountIsNotActiveException();
        }
        /*
        if ($user instanceof AppUser && $user->getIsActive() == false)
        {
            $code = $this->cryptDecryptHashing->hashMomentIt($user->getMail());
            $user->setCode($code);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $autoReplyByMail->sendActivationLink($user);

            throw new AccountIsNotActive();
        }
        if (!$user instanceof AppUser) {
            return;
        }
        */
    }
}
