<?php

namespace AppBundle\Controller;

use AppBundle\Data\LocationHandler;
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

        if($json != null || $json['apiKey'] != "oordeel maar niet waar") {
            $name = $json['name'];
            $locations = $json['locations'];

            if(!empty($locations)) {
                $mongoManager = $this->get('doctrine_mongodb')->getManager();

                LocationHandler::persistLocations($mongoManager, $name, $locations);
            }

            $response = new Response();

            $response->setStatusCode(200);
            return $response;
        }

        $response = new Response();

        $response->setStatusCode(400);

        return $response;
    }

    /**
     * @Route("/api/route/{name}", name="routeGet")
     * @Method({"GET"})
     */
    public function routeActionGet() {

    }
}
