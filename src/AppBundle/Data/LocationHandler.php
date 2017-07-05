<?php

namespace AppBundle\Data;


use AppBundle\Document\Location;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

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
    static function persistLocations(ObjectManager $mongoManager, $personName, $locationsJSON): array {
        $locationsDoc = array();

        foreach($locationsJSON as $json) {
            $location = new Location();

            $location->setPersonName(strtolower($personName));
            $location->setLatitude($json['lat']);
            $location->setLongitude($json['lon']);
            $location->setAltitude($json['alt']);
            $location->setAccuracy($json['acc']);

            $date = new \DateTime();
            $date->setTimestamp($json['time']);
            $timezone = new \DateTimeZone($json['timezone']);
            $date->setTimezone($timezone);

            $location->setDate($date);

            $locationsDoc[] = $location;
            $mongoManager->persist($location);
        }
        $mongoManager->flush();

        return $locationsDoc;
    }


    static function getRouteForPerson(ObjectRepository $repository, $personName): string {
        $locations = $repository->findBy(array('personName' => $personName));
        $routeJSON = array();

        foreach($locations as $location) {
            $date = $location->getDate();

            $timestamp = $date->getTimestamp();
            $timezone = $date->getTimezone();

            $routeJSON[] = array(
                "lat" => $location->getLatitude(),
                "lon" => $location->getLatitude()
            );
        }

        $route = array('route' => $routeJSON);

        return json_encode($route);
    }

}