<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Applisun\AireJeuxBundle\Form\Type\ContactType;

use Applisun\AireJeuxBundle\Highmap\Highmap;
use Zend\Json\Expr;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {   
        $em = $this->getDoctrine()->getEntityManager();
        
        $ob = new HighMap();
        $ob->chart->renderTo('map');
        $ob->chart->backgroundColor(null);
        $ob->title->text('');
        $ob->credits->enabled(false);
        $ob->legend->enabled(false);
        $ob->navigation(array("buttonOptions" => array("enabled" => false)));
        $ob->mapNavigation(array("enabled" => false, "enableMouseWheelZoom" => false, "enableTouchZoom" => true, "buttonOptions" => array("verticalAlign" => "bottom")));
        
        $ob->plotOptions(array("series" => array(
                                    "point" => array(
                                        "events" => array(
                                            "click" => new Expr('function (){ '
                                                    . 'document.location.href=Routing.generate(\'departement_show\', { slug: this.slug, id: this.id, page: 1 }); '
                                                    . '}')
                                            )
                                        )
                                )
                            )
                        );
        
        $data= $em->getRepository('ApplisunAireJeuxBundle:Departement')->getAllAires();
        
        $data = array(array(
                "data" => $data,                  
                "mapData" => new Expr('Highcharts.maps[\'countries/fr/fr-all-all\']'),
                "joinBy" => "hc-key",
                "name" => "Aire de jeux",
                "cursor" => "pointer",
                "states" => array(
                    "hover" => array(
                        "color" => "#FBB03B"                       
                    )
                ),
                "dataLabels" => array(
                    "enabled" => true,
                    "format" => ""
                ),
                "tooltip" => array(
                    "valueSuffix" => ' aire(s)'    
                )
                ));
        $data2 = array(
                    "name" => "Separators",
                    "type" => "mapline",
                    "data" => new Expr('Highcharts.geojson(Highcharts.maps[\'countries/fr/fr-all-all\'], \'mapline\')'),
                    "color" => "silver",
                    "showInLegend" => false,
                    "enableMouseTracking" => false
                );
        
        array_push($data, $data2);
        
        $ob->series($data);   
        
        
        //return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig');
        return  $this->render('ApplisunAireJeuxBundle:Default:index.html.twig', array(
            'carte' => $ob));
    }
    
    /**
     * @Route("/users/{page}", name="users")
     */
    public function usersAction($page = 1)
    {  
        $em = $this->getDoctrine()->getEntityManager();
        
        $users= $em->getRepository('ApplisunAireJeuxBundle:User')->getAllActiveUsers($this->container);
        
        /*echo '<pre>';
        \Doctrine\Common\Util\Debug::dump($users, 3);
        echo '</pre>';
        exit;*/
        
        return  $this->render('ApplisunAireJeuxBundle:Default:users.html.twig', array(
            'users' => $users));
    }
    
    /**
     * @Route("/user/{id}", name="user")
     */
    public function userAction($id)
    {  
        $em = $this->getDoctrine()->getEntityManager();
        
        $user= $em->getRepository('ApplisunAireJeuxBundle:User')->findOneById($id);
        return  $this->render('ApplisunAireJeuxBundle:Default:user.html.twig', array(
            'user' => $user));
    }
    
    /**
     * @Route("/application", name="application")
     */
    public function applicationAction()
    {  
        return  $this->render('ApplisunAireJeuxBundle:Static:application.html.twig');
    }
    
    /**
     * @Route("/mentions", name="mentions")
     */
    public function mentionsAction()
    {  
        return  $this->render('ApplisunAireJeuxBundle:Static:mentions.html.twig');
    }
    
    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {  
        $form = $this->createForm(new ContactType());

        $request = $this->getRequest();
        
        if ($request->getMethod() == 'GET') {

            $form->bind($request);

            if ($form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();

                $data = $request->query->get('applisun_contact_form');                
                
                //envoi du mail 
                $message = \Swift_Message::newInstance()
                    ->setSubject('AireJeux.com : message de '.$data['nom'])
                    ->setFrom($data['email'])
                    ->setTo('frederic.teissier@live.fr')
                    ->setBody($this->renderView('ApplisunAireJeuxBundle:Default:email.txt.twig', array('data' => $data)));
                $this->get('mailer')->send($message);
             
                return $this->render('ApplisunAireJeuxBundle:Default:contactOK.html.twig', array('data' => $data));
            }
        }
        
        return $this->render('ApplisunAireJeuxBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }
    
    
    public function numbersAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $users= $em->getRepository('ApplisunAireJeuxBundle:User')->findAll();
        $aires = $em->getRepository('ApplisunAireJeuxBundle:Aire')->findAll();
        
        $response = $this->render('ApplisunAireJeuxBundle:Default:numbers.html.twig', array('nbUsers' => count($users), 'nbAires' => count($aires)));
        
        $response->setSharedMaxAge(600);
        
        return $response;
    }
    
}
