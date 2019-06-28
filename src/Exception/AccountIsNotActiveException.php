<?php
namespace App\Exception;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountIsNotActiveException extends AccountStatusException
{
    public function getMessageKey()
    {
        return 'Votre compte n\'est pas encore activé! Un nouveau mail vient de vous être envoyé. Veuillez cliquer sur son lien d\'activation pour pouvoir accéder à votre compte.';
    }
}