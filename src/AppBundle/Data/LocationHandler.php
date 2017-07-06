<?php

namespace AppBundle\Data;


use AppBundle\Document\Location;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

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
    static function persistLocations(ObjectManager $mongoManager, $personName, $locationsJSON, LoggerInterface $logger): array {
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
            $timezone = new \DateTimeZone($json['timezone']);

            $logger->info("timezone in JSON: " . $json['timezone']);
            $logger->info("timezone in DateTimeZone: " . $timezone->getName() . ' ' . $timezone->getOffset($date));

            $date->setTimezone($timezone);

            $location->setDate($date);

            $locationsDoc[] = $location;
            $mongoManager->persist($location);
        }
        $mongoManager->flush();

        return $locationsDoc;
    }


    static function getRouteForPerson(ObjectManager $manager, $personName): string {
        $queryBuilder = $manager->createQueryBuilder("AppBundle:Location");

        $locations = $queryBuilder
            ->field('personName')->equals(ucwords($personName))
            ->sort('date', 'asc')
            ->getQuery()->execute();

        $routeJSON = array();
        $lastLocation = null;

        foreach($locations as $location) {
            $routeJSON[] = array(
                "lat" => $location->getLatitude(),
                "lon" => $location->getLatitude()
            );
            $lastLocation = $location;
        }

        if($lastLocation != null) {
            $date = $lastLocation->getDate();
            $timestamp = $date->getTimestamp();
            $timezoneOffset = $date->getTimezone()->getOffset($date);
            $timezoneName = $date->getTimezone()->getName();
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

    static function getUniquePersons(ObjectManager $manager): string {
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

}