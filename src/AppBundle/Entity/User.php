<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="panel_users")
 */
class User implements UserInterface, \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $role;

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => [__CLASS__]]);
    }

    public function getRoles() {
        return [$this->role];
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        // Neither bcrypt/argon2i need a salt, now if only Debian shipped
        // a Dovecot compatible with any of them...
        return null;
    }

    public function getUsername() {
        return $this->username;
    }

    public function eraseCredentials() {

    }

    /**
     * @return mixed
     */
    public function isEnabled() {
        return $this->enabled;
    }
}