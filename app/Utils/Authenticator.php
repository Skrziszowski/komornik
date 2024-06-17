<?php

declare(strict_types=1);

namespace App\Utils;

use Nette;
use Nette\Security\SimpleIdentity;
use Nette\Security\Passwords;
use App\Models\UserFacade;

class Authenticator implements Nette\Security\Authenticator
{
    public function __construct(private UserFacade $userFacade, private Passwords $passwords)
    {    
    }

    public function authenticate(string $email, string $password): SimpleIdentity
    {
         $user = $this->userFacade->getUserByEmail($email);

         if(!$user || !$this->passwords->verify($password, $user->password)) {
            throw new Nette\Security\AuthenticationException('Neplatné přihlašovací údaje');
         }
 
         return new Nette\Security\SimpleIdentity(
            $user->user_id,
            $user->role_id,
            ['email' => $user->email]
         );
    }

    public function add(array $userData): void
	{
        if ($this->userFacade->getUserByEmail($userData['email'])) {
            throw new DuplicateEmailException;
        }

		try {
			$this->userFacade->addUser($userData);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateEmailException;
		}
	}
}
/**
 * Vlastní výjimka pro duplicitní e-mailové adresy
 */
class DuplicateEmailException extends \Exception
{
}