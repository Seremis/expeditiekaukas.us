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
     * @MongoDB\Field(type="string")
     */
    protected $timezoneStart;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $localEndTime;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $timezoneEnd;

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

    /**
     * Set timezoneStart
     *
     * @param string $timezoneStart
     * @return $this
     */
    public function setTimezoneStart($timezoneStart)
    {
        $this->timezoneStart = $timezoneStart;
        return $this;
    }

    /**
     * Get timezoneStart
     *
     * @return string $timezoneStart
     */
    public function getTimezoneStart()
    {
        return $this->timezoneStart;
    }

    /**
     * Set timezoneEnd
     *
     * @param string $timezoneEnd
     * @return $this
     */
    public function setTimezoneEnd($timezoneEnd)
    {
        $this->timezoneEnd = $timezoneEnd;
        return $this;
    }

    /**
     * Get timezoneEnd
     *
     * @return string $timezoneEnd
     */
    public function getTimezoneEnd()
    {
        return $this->timezoneEnd;
    }
}
