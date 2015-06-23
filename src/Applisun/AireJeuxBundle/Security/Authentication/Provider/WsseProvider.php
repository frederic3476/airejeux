<?php

namespace Applisun\AireJeuxBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Applisun\AireJeuxBundle\Security\Authentication\Token\WsseUserToken;

class WsseProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $cacheDir)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            //var_dump($token->digest); exit;
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }
        
        throw new AuthenticationException('The WSSE authentication failed.');
    }
    
     protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Expire le timestamp après 24h
        if (time() - strtotime($created) > 86400) {
            return false;
        }
        
        // Valide que le nonce est unique dans les 5 minutes
        if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + 86400 > time()) {
            //echo ('exist'); exit;
            throw new NonceExpiredException('Previously used nonce detected');
        }
        
        file_put_contents($this->cacheDir.'/'.$nonce, time());
        //var_dump($digest); 
        // Valide le Secret
        //$expected = base64_encode(sha1($nonce.$created.$secret, true));
        $expected = base64_encode(sha1(base64_decode($nonce).$created.$secret, true));
        //var_dump($expected); exit;
        return $digest === $expected;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}
