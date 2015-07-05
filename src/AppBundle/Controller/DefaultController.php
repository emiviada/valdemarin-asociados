<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\BuildingSearcherType;
use AppBundle\Form\Type\AuctionSearcherType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // Get featured properties
        $em = $this->getDoctrine()->getManager();
		$featured = $em->getRepository('AppBundle:Building')->getFeatured();

		// Searcher forms
		$buildingSearcherForm = $this->createForm(new BuildingSearcherType());
		$auctionSearcherForm = $this->createForm(new AuctionSearcherType());

        return $this->render('default/index.html.twig', array(
        	'featured' => $featured,
        	'buildingSearcherForm' => $buildingSearcherForm->createView(),
        	'auctionSearcherForm' => $auctionSearcherForm->createView()
    	));
    }

    /**
	 * @Route("/inmuebles", name="listing_building")
     */
    public function listingBuildingAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$results = $em->getRepository('AppBundle:Building')->findAll();

		$viewDetailRoute = 'view_building';
		// Sidebar form
		$formRoute = $this->get('router')->generate('building_search');
		$form = $this->createForm(new BuildingSearcherType());

    	return $this->render('search/listing.html.twig', array(
    		'type' => 'Inmuebles',
    		'results' => $results,
    		'view_detail_route' => $viewDetailRoute,
    		'form_route' => $formRoute,
    		'form' => $form->createView()
		));
    }

    /**
	 * @Route("/subastas", name="listing_auction")
     */
    public function listingAuctionAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$results = $em->getRepository('AppBundle:Auction')->findAll();

		$viewDetailRoute = 'view_auction';
		// Sidebar form
		$formRoute = $this->get('router')->generate('auction_search');
		$form = $this->createForm(new AuctionSearcherType());

    	return $this->render('search/listing.html.twig', array(
    		'type' => 'Subastas',
    		'results' => $results,
    		'view_detail_route' => $viewDetailRoute,
    		'form_route' => $formRoute,
    		'form' => $form->createView()
		));
    }

    /**
	 * @Route("/inmueble/{slug}", name="view_building")
     */
    public function viewBuildingAction($slug)
    {
		$em = $this->getDoctrine()->getManager();
    	$building = $em->getRepository('AppBundle:Building')->findOneBySlug($slug);

	    if (!$building) {
	        throw $this->createNotFoundException(
	            'No building found for reference '.$slug
	        );
	    }

	    // Sidebar form
		$formRoute = $this->get('router')->generate('building_search');
		$form = $this->createForm(new BuildingSearcherType());

	    return $this->render('default/view_building.html.twig', array(
	    	'building' => $building,
	    	'form_route' => $formRoute,
    		'form' => $form->createView()
    	));
    }

    /**
	 * @Route("/subasta/{slug}", name="view_auction")
     */
    public function viewAuctionAction($slug)
    {
		$em = $this->getDoctrine()->getManager();
    	$auction = $em->getRepository('AppBundle:Auction')->findOneBySlug($slug);

	    if (!$auction) {
	        throw $this->createNotFoundException(
	            'No auction found for name '.$slug
	        );
	    }

	    // Sidebar form
		$formRoute = $this->get('router')->generate('auction_search');
		$form = $this->createForm(new AuctionSearcherType());

	    return $this->render('default/view_auction.html.twig', array(
	    	'auction' => $auction,
	    	'form_route' => $formRoute,
    		'form' => $form->createView()
    	));
    }

    /**
     * @Route("/inmuebles/buscar/", name="building_search")
     */
    public function buildingSearchAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$buildingSearcher = $request->request->get('building_searcher');
		$results = $em->getRepository('AppBundle:Building')->search($buildingSearcher);

		$viewDetailRoute = 'view_building';
		// Sidebar form
		$formRoute = $this->get('router')->generate('building_search');
		$form = $this->createForm(new BuildingSearcherType());
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
		}

    	return $this->render('search/listing.html.twig', array(
    		'type' => 'Inmuebles',
    		'results' => $results,
    		'view_detail_route' => $viewDetailRoute,
    		'form_route' => $formRoute,
    		'form' => $form->createView()
		));
    }

    /**
     * @Route("/subastas/buscar/", name="auction_search")
     */
    public function auctionSearchAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$auctionSearcher = $request->request->get('auction_searcher');
		$results = $em->getRepository('AppBundle:Auction')->search($auctionSearcher);

		$viewDetailRoute = 'view_auction';
		// Sidebar form
		$formRoute = $this->get('router')->generate('auction_search');
		$form = $this->createForm(new AuctionSearcherType());
		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
		}

    	return $this->render('search/listing.html.twig', array(
    		'type' => 'Subastas',
    		'results' => $results,
    		'view_detail_route' => $viewDetailRoute,
    		'form_route' => $formRoute,
    		'form' => $form->createView()
		));
    }

    /**
     * @Route("/contacto", name="contact")
     */
    public function contactAction()
    {
    	return $this->render('default/contact.html.twig');
    }
}
