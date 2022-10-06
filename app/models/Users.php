<?php

namespace App\Models;

use App\Lib\Slug;
use Phalcon\Db\Column;
use Phalcon\Mvc\ModelInterface;

class Users extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $password;



    /**
     *
     * @var integer 0 or 1
     * @Column(type="integer", length=1, nullable=false)
     */
    public $active;

    /**
     *
     * @var integer 0 or 1
     * @Column(type="integer", length=1, nullable=false)
     */
    // test ban
    public $banned;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('users');

    }

    //  Hash password before insert data
    public function beforeCreate()
    {
        $this->password = $this->getDI()->getSecurity()->hash($this->password);
    }

}
