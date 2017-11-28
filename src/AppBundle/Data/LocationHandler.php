<?php

namespace AppBundle\Data;


use AppBundle\Document\Location;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;


class LocationHandler {

    /**
     * An incoming request should look like:
     *  {
     *      apiKey: "oordeel maar niet waar"
     *      name: "Maurice" //person name
     *      locations: [
     *          {
     *              lat: 53.237792      //Latitude
     *              lon: 6.557532       //Longitude
     *              alt: 10.0           //Altitude
     *              acc: 4              //Accuracy
     *              time: 1499100060    //Timestamp
     *          },
     *          ...
     *      ]
     *  }
     */
    static function persistLocations(DocumentManager $mongoManager, $personName, $locationsJSON): array {
        $locationsDoc = array();

        foreach($locationsJSON as $json) {
            $location = new Location();

            $location->setPersonName(ucwords($personName));
            $location->setLatitude($json['lat']);
            $location->setLongitude($json['lon']);
            $location->setAltitude($json['alt']);
            $location->setAccuracy($json['acc']);

            $date = new \DateTime();
            $date->setTimestamp($json['time']);
            $date->setTimezone(new \DateTimeZone("UTC"));
            $location->setDate($date);//DateTimeZone doesn't get stored in MongoDB, separate field for timezone necessary.
            $location->setTimezone($json['timezone']);

            $locationsDoc[] = $location;
            $mongoManager->persist($location);
        }
        $mongoManager->flush();

        return $locationsDoc;
    }

    /**
     * Creates a new Location object in the database that is a copy of the last, except for the time.
     * @param DocumentManager $mongoManager
     * @param $personName
     */
    static function pingLocation(DocumentManager $mongoManager, $personName) {
        $lastLocation = LocationHandler::getLastLocationForPerson($mongoManager, $personName)->copy();

        $date = new \DateTime(); //time in milliseconds
        $date->setTimestamp(time() * 1000);
        $lastLocation->setDate($date);

        $mongoManager->persist($lastLocation);
        $mongoManager->flush();
    }

    static function getLastLocationForPerson(DocumentManager $mongoManager, $personName): Location {
        $queryBuilder = $mongoManager->createQueryBuilder("AppBundle:Location");

        $locations = $queryBuilder
            ->field('personName')->equals(ucwords($personName))
            ->sort('date', 'desc')
            ->limit(1)
            ->getQuery()
            ->execute();

        $location = $locations->getNext();

        return isset($location) ? $location : null;
    }


    static function getRouteForPerson(DocumentManager $manager, $personName): string {
        $queryBuilder = $manager->createQueryBuilder("AppBundle:Location");

        $query = $queryBuilder
            ->field('personName')->equals(ucwords($personName))
            ->sort('date', 'asc')
            ->getQuery();

        $iterableResult = $query->getIterator();

        $routeJSON = array();
        $lastLocation = null;

        foreach ($iterableResult as $location) {

            $routeJSON[] = array(
                "lat" => $location->getLatitude(),
                "lon" => $location->getLongitude()
            );
            $lastLocation = $location;

            $manager->detach($location);
        }

        if($lastLocation != null) {
            $date = $lastLocation->getDate();
            $timestamp = $date->getTimestamp();

            $timezone = new \DateTimeZone($lastLocation->getTimezone());

            $timezoneOffset = $timezone->getOffset($date);
            $timezoneName = $timezone->getName();
        } else {
            $timestamp = 0;
            $timezoneOffset = 0;
            $timezoneName = "";
        }

        $route = array(
            'personName' => $personName,
            'lastUpdateTime' => $timestamp,
            'lastUpdateTimezoneOffset' => $timezoneOffset,
            'lastUpdateTimezoneName' => $timezoneName,
            'route' => $routeJSON
        );

        return json_encode($route);
    }

    static function getUniquePersons(DocumentManager $manager): string {
        $queryBuilder = $manager->createQueryBuilder("AppBundle:Location");

        $persons = $queryBuilder
            ->distinct('personName')
            ->getQuery()->execute();

        $personsJSON = array();

        foreach($persons as $person) {
            $personsJSON[] = $person;
        }

        sort($personsJSON);

        $json = array(
            'people' => $personsJSON
        );

        return json_encode($json);
    }

    static function getFullRouteForPerson(DocumentManager $manager, $personName): string {
        $queryBuilder = $manager->createQueryBuilder("AppBundle:Location");

        $query = $queryBuilder
            ->field('personName')->equals(ucwords($personName))
            ->sort('date', 'asc')
            ->getQuery();

        $iterableResult = $query->getIterator();

        $routeJSON = array();
        $lastLocation = null;

        foreach ($iterableResult as $location) {
            $routeJSON[] = array(
                "lat" => $location->getLatitude(),
                "lon" => $location->getLongitude(),
                "alt" => $location->getAltitude(),
                "acc" => $location->getAccuracy(),
                "person" => $location->getPersonName(),
                "stamp" => $location->getDate()->getTimestamp(),
                "timezone" => $location->getTimeZone()
            );
            $lastLocation = $location;

            $manager->detach($location);
        }

        if($lastLocation != null) {
            $date = $lastLocation->getDate();
            $timestamp = $date->getTimestamp();

            $timezone = new \DateTimeZone($lastLocation->getTimezone());

            $timezoneOffset = $timezone->getOffset($date);
            $timezoneName = $timezone->getName();
        } else {
            $timestamp = 0;
            $timezoneOffset = 0;
            $timezoneName = "";
        }

        $route = array(
            'personName' => $personName,
            'lastUpdateTime' => $timestamp,
            'lastUpdateTimezoneOffset' => $timezoneOffset,
            'lastUpdateTimezoneName' => $timezoneName,
            'route' => $routeJSON
        );

        return json_encode($route);
    }
}
