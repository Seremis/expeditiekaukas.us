<?php
namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class TimeFrame {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $localStartTime;

    /**
     * @MongoDB\Field(type="timestamp")
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
     * @param timestamp $localStartTime
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
     * @return timestamp $localStartTime
     */
    public function getLocalStartTime()
    {
        return $this->localStartTime;
    }

    /**
     * Set localEndTime
     *
     * @param timestamp $localEndTime
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
     * @return timestamp $localEndTime
     */
    public function getLocalEndTime()
    {
        return $this->localEndTime;
    }
}
