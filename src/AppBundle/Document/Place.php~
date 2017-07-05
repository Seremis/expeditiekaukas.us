<?php
namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @MongoDB\Field(type="int")
     */
    protected $altitude;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    protected $timestamp;

    /**
     * @MongoDB\Field(type="int")
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
}