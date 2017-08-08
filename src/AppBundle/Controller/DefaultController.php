<?php

namespace AppBundle\Controller;

use AppBundle\Data\LocationHandler;
use AppBundle\Document\Location;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction() {
        return $this->redirect('/nl');
    }

    /**
     * @Route("/{_locale}/", name="home", defaults={"_locale": "nl"}, requirements={"_locale": "nl|en|ru"})
     */
    public function indexActionLocalized($_locale) {

        $name = $this->get('translator')->trans('name');
        $flagName = $this->get('translator')->trans('flag.name');

        $mapsLayoutName = $this->get('translator')->trans('mapslayout.name');
        $errorLoadingTryAgain = $this->get('translator')->trans('map.loading.error');
        $lastseen = $this->get('translator')->trans('map.lastseen');
        $loading = $this->get('translator')->trans('map.loading');

        $otherLocales = array();
        $otherLocaleFlags = array();

        foreach(array('ru', 'en', 'nl') as $l) {
            if($l != $_locale) {
                $otherLocales[] = $l;
                $otherLocaleFlags[] = $this->get('translator')->trans('flag.name', array(),null, $l);
            }
        }

        return $this->render('default/index.html.twig', [
            '_locale' => $_locale,
            'name' => $name,
            'flag' => $flagName,
            'otherLocale1' => $otherLocales[0],
            'otherLocale2' => $otherLocales[1],
            'otherLocale1Flag' => $otherLocaleFlags[0],
            'otherLocale2Flag' => $otherLocaleFlags[1],
            'mapslayoutName' => $mapsLayoutName,
            'errorLoadingTryAgain' => $errorLoadingTryAgain,
            'lastseen' => $lastseen,
            'loading' => $loading,
        ]);
    }

    /**
     * @Route("/api/location", name="locationPost")
     * @Method("POST")
     *
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
    public function locationActionPost(Request $request) {
        $jsonString = $request->getContent();

        $json = json_decode($jsonString, $assoc = true);

        if($json != null && $json['apiKey'] == "oordeel maar niet waar") {
            if(isset($json['locations'])) {
                $name = $json['name'];
                $locations = $json['locations'];

                if (!empty($locations)) {
                    $mongoManager = $this->get('doctrine_mongodb')->getManager();

                    LocationHandler::persistLocations($mongoManager, $name, $locations);
                }

                $response = new Response();

                $response->setStatusCode(200);
                return $response;
            } else {
                $name = $json['name'];

                $mongoManager = $this->get('doctrine_mongodb')->getManager();

                LocationHandler::pingLocation($mongoManager, $name);

                $response = new Response();

                $response->setStatusCode(200);
                return $response;
            }
        }

        $response = new Response();

        $response->setStatusCode(400);

        return $response;
    }

    /**
     * @Route("/api/location/", name="locationPostSlash")
     * @Method("POST")
     */
    public function locationActionPostSlash(Request $request) {
        return $this->locationActionPost($request);
    }

    /**
     * @Route("/api/route/{name}", name="routeGet")
     * @Method("GET")
     */
    public function routeActionGet($name) {
        $response = new Response();

        $manager = $this->get('doctrine_mongodb')->getManager();

        $json = LocationHandler::getRouteForPerson($manager, $name);

        $response->setContent($json);

        $response->setStatusCode(200);

        return $response;
    }

    /**
     * Trailing slash routeActionGet
     * @Route("/api/route/{name}/", name="routeGetSlash")
     * @Method("GET")
     */
    public function routeActionGetSlash() {
        return $this->routeActionGet();
    }

    /**
     * @Route("/api/person", name="personGet")
     * @Method("GET")
     */
    public function personActionGet() {
        $response = new Response();

        $manager = $this->get('doctrine_mongodb')->getManager();

        $json = LocationHandler::getUniquePersons($manager);

        $response->setContent($json);
        $response->setStatusCode(200);

        return $response;
    }

    /**
     * Trailing slash personActionGet
     * @Route("/api/person/", name="personGet")
     * @Method("GET")
     */
    public function personActionGetSlash() {
        return $this->personActionGet();
    }
}
