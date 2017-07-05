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
            $location->setTimestamp($json['time']);

            $locationsDoc[] = $location;
            $mongoManager->persist($location);
        }
        $mongoManager->flush();

        return $locationsDoc;
    }


    static function getLocations(ObjectRepository $repository, $personName): array {
        $locations = $repository->getByPersonName($personName);
        $locationsJSON = array();

        foreach($locations as $location) {
            $locationsJSON[] = array(
                "lat" => $location->getLatitude(),
                "lon" => $location->getLatitude(),
                "alt" => $location->getAltitude(),
                "acc" => $location->getAccuracy(),
                "time" => $location->getTimestamp()
            );
        }
    }

}