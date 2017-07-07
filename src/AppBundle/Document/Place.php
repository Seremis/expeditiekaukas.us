<?php
namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;


/**
 * @MongoDB\Document
 */
class Place {

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $personName;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $placeName;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $latitude;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $longitude;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $altitude;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $date;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $timezone;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $accuracy;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $distanceSincePrevPlace;

    /**
     * @MongoDB\ReferenceMany(targetDocument="TimeFrame")
     */
    protected $timeframes = array();
    public function __construct()
    {
        $this->timeframes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set personName
     *
     * @param string $personName
     * @return $this
     */
    public function setPersonName($personName)
    {
        $this->personName = $personName;
        return $this;
    }

    /**
     * Get personName
     *
     * @return string $personName
     */
    public function getPersonName()
    {
        return $this->personName;
    }

    /**
     * Set placeName
     *
     * @param string $placeName
     * @return $this
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;
        return $this;
    }

    /**
     * Get placeName
     *
     * @return string $placeName
     */
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set altitude
     *
     * @param int $altitude
     * @return $this
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
        return $this;
    }

    /**
     * Get altitude
     *
     * @return int $altitude
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set accuracy
     *
     * @param int $accuracy
     * @return $this
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;
        return $this;
    }

    /**
     * Get accuracy
     *
     * @return int $accuracy
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * Set distanceSincePrevPlace
     *
     * @param float $distanceSincePrevPlace
     * @return $this
     */
    public function setDistanceSincePrevPlace($distanceSincePrevPlace)
    {
        $this->distanceSincePrevPlace = $distanceSincePrevPlace;
        return $this;
    }

    /**
     * Get distanceSincePrevPlace
     *
     * @return float $distanceSincePrevPlace
     */
    public function getDistanceSincePrevPlace()
    {
        return $this->distanceSincePrevPlace;
    }

    /**
     * Add timeframe
     *
     * @param AppBundle\Document\TimeFrame $timeframe
     */
    public function addTimeframe(\AppBundle\Document\TimeFrame $timeframe)
    {
        $this->timeframes[] = $timeframe;
    }

    /**
     * Remove timeframe
     *
     * @param AppBundle\Document\TimeFrame $timeframe
     */
    public function removeTimeframe(\AppBundle\Document\TimeFrame $timeframe)
    {
        $this->timeframes->removeElement($timeframe);
    }

    /**
     * Get timeframes
     *
     * @return \Doctrine\Common\Collections\Collection $timeframes
     */
    public function getTimeframes()
    {
        return $this->timeframes;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Get timezone
     *
     * @return string $timezone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
}
