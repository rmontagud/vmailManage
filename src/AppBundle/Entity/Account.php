<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Created by PhpStorm.
 * User: andy
 * Date: 02.07.17
 * Time: 19:21
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="accounts")
 */
class Account
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=64)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * ORM\Column(type="string", length=255)
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="accounts")
     * ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumn(name="domain", referencedColumnName="domain")
     */
    private $domain;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $quota;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sendonly;

    public function __construct()
    {
        $this->quota = 2048;
    }

    /**
     *
     * @param $id
     * @param $username
     * @param $domain
     * @param $password
     * @param $quota
     * @param $enabled
     * @param $sendonly
     */
//    public function set($id, $username, $domain, $password, $quota, $enabled, $sendonly)
//    {
//        $this->id = $id;
//        $this->username = $username;
//        $this->domain = $domain;
//        $this->password = $password;
//        $this->quota = $quota;
//        $this->enabled = $enabled;
//        $this->sendonly = $sendonly;
//    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        // Thomas Leistter's guide uses SHA-512 for password hashing, so i'm respecting that but
        // if you come with older virtual mail setups with other systems there's a chance you're
        if ((!empty($password)) && (!preg_match('/^({([A-Z0-9-]{4,14})}.*)\$/', $password))) {
            // We have installed the random_bytes polyfill, so why don't we use it?
            $salt = substr(sha1(random_bytes(20)), 0, 16);
            $password = '{SHA512-CRYPT}'. crypt($password, "$6$$salt");
        } elseif (empty($password)) {
            // Prevent overriding the password with a blank password
            $password = $this->password;
        }

        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param mixed $quota
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getSendonly()
    {
        return $this->sendonly;
    }

    /**
     * @param mixed $sendonly
     */
    public function setSendonly($sendonly)
    {
        $this->sendonly = $sendonly;
    }

}