<?php

namespace App\Entity\Traits;

use \DateTime;

trait Timestampable
{
    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $updated_at;

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUpdatedAt(DateTime $datetime)
    {
        $this->updated_at = $datetime;
    }
}
