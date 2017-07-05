<?php
namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;


/**
 * @MongoDB\Document
 */
class TimeFrame {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $localStartTime;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $localEndTime;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set localStartTime
     *
     * @param \DateTime $localStartTime
     * @return $this
     */
    public function setLocalStartTime($localStartTime)
    {
        $this->localStartTime = $localStartTime;
        return $this;
    }

    /**
     * Get localStartTime
     *
     * @return \DateTime $localStartTime
     */
    public function getLocalStartTime()
    {
        return $this->localStartTime;
    }

    /**
     * Set localEndTime
     *
     * @param \DateTime $localEndTime
     * @return $this
     */
    public function setLocalEndTime($localEndTime)
    {
        $this->localEndTime = $localEndTime;
        return $this;
    }

    /**
     * Get localEndTime
     *
     * @return \DateTime $localEndTime
     */
    public function getLocalEndTime()
    {
        return $this->localEndTime;
    }
}
