<?php

namespace Applisun\AireJeuxBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAireVoter implements VoterInterface
{
    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return Boolean true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return 'USER_CAN_VOTE' == $attribute;
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return Boolean true if this Voter can process the class
    */
    public function supportsClass($class)
    {
        return 'Applisun\AireJeuxBundle\Entity\Aire' == $class;
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param object         $object     The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return integer either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
    */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();

        // si pas d'utilisateur on s'abstient
        if ( ! $user instanceof UserInterface) {
            return self::ACCESS_ABSTAIN;
        }

        // si object n'est pas une aire on s'abstient
        if ( ! $this->supportsClass(get_class($object) )) {
            return self::ACCESS_ABSTAIN;
        }

        // on cherche si un attribut est supporté
        $i = 0;
        $supported = false;

        while ($i<count($attributes) && !$supported) {
            $supported = $this->supportsAttribute($attributes[$i]);
            $i++;
        }

        // pas d'attribut supporter on s'abstient
        if (!$supported) {
            return self::ACCESS_ABSTAIN;
        }

        // attribut supporté et object supporté, donc on vérifie si l'utilisateur peut voter sur l'aire
        return $object->hasUserAlreadyVoted($user) ? self::ACCESS_DENIED : self::ACCESS_GRANTED;
    }
}
