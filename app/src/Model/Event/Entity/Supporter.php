<?php

namespace App\Model\Event\Entity;

use App\Model\Email;
use App\Model\Twitter;

class Supporter
{
    public $id;

    private $name;

    private $url;

    private $twitter;

    private $email;

    private $logo;

    public function __construct($name, $url, Twitter $twitter, Email $email, $logo)
    {
        $this->name     = $name;
        $this->url      = $url;
        $this->twitter  = $twitter;
        $this->email    = $email;
        $this->logo     = $logo;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $params
     * @return Supporter
     */
    public static function create(array $params = []) : Supporter
    {
        $class = new self(
            $params['name'] ?? null,
            $params['url'] ?? null,
            new Twitter($params['twitter'] ?? null) ?? null,
            new Email($params['email'] ?? null) ?? null,
            $params['logo'] ?? null
        );

        $class->setId($params['id'] ?? null);

        return $class;
    }

}