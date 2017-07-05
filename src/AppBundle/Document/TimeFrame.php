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
}