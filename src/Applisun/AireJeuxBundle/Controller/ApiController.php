<?php

namespace Applisun\AireJeuxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use Symfony\Component\Validator\ConstraintViolationList;

use Applisun\AireJeuxBundle\Entity\Aire;
use Applisun\AireJeuxBundle\Entity\User as User;

class ApiController extends Controller {
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the overall Aire List by ville",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ville is not found"
     *   }
     * )
     */
    
    public function getAiresAction($ville_id, $_format="json")
    {
        $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getAllAireByVille($ville_id);
        
        //$user = $this->getUser();
        //echo 'toto'; 
        //var_dump($user); exit;
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aires, $_format);
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return Aire by id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the aire is not found"
     *   }
     * )
     */
    
    public function getAireAction($id, $_format="json")
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->getAire($id);
        
        if (!$aire instanceof Aire) {
            throw $this->createNotFoundException('Aucune aire trouvée !');
        }
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aire, $_format);
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return aire's comments",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the aire is not found"
     *   }
     * )
     */
    
    public function getCommentaireAction($aire_id, $_format="json")
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->getAire($aire_id);
        
        if (!$aire instanceof Aire) {
            throw $this->createNotFoundException('Aucune aire trouvée !');
        }
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aire->getComments(), $_format);
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return ville list by query",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no result"
     *   }
     * )
     */
    
    public function getVilleAction($query, $_format="json")
    {
        $villes = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Ville')->getVilleByCompletion($query);
        
        if (count($villes) == 0) {
            return new response('no result', 404);
        }
        
        return $this->render('ApplisunAireJeuxBundle:Ville:list.json.twig', array('villes' => $villes));                
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new aire from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="nom", nullable=false, strict=true, description="name")
     * @RequestParam(name="ville_str", nullable=false, strict=true, description="ville name and code")
     * @RequestParam(name="surface", nullable=true, strict=true, description="Surface")
     * @RequestParam(name="longitude", nullable=false, strict=true, description="Longitude")
     * @RequestParam(name="latitude", nullable=false, strict=true, description="Latitude")
     * @RequestParam(name="age_min", nullable=true, strict=true, description="Age minimum")
     * @RequestParam(name="age_max", nullable=true, strict=true, description="Age maximum")
     * @RequestParam(name="img64", nullable=true, strict=true, description="Image en base 64")
     */
    
    public function postAireAction(ParamFetcher $paramFetcher)
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->createAire();
        
        $view = View::create();        
        
        $aire->setNom($paramFetcher->get('nom'));
        $aire->setSurface($paramFetcher->get('surface'));
        $aire->setLongitude($paramFetcher->get('longitude'));
        $aire->setLatitude($paramFetcher->get('latitude'));
        $aire->setAgeMin($paramFetcher->get('age_min'));
        $aire->setAgeMax($paramFetcher->get('age_max'));
        
        //get code
        $tab = explode('|', $paramFetcher->get('ville_str'));
        $nom = $tab[0];
        $code = $tab[1];
        
        $ville = $this->getDoctrine()->getEntityManager()
            ->getRepository('ApplisunAireJeuxBundle:Ville')
            ->findOneBy(array('nom' => $nom,'code' => $code));
        $aire->setVille($ville);
        
        $user = $this->getUser();        
        $aire->setUser($user);
        
        if ($paramFetcher->get('img64') && $paramFetcher->get('img64') !== ''){
            $image = imagecreatefromstring(base64_decode($paramFetcher->get('img64')));
            $file_name = uniqid().'.png';
            imagepng($image, $aire->getUploadRootDir()."/".$file_name);
            $aire->setFileName($file_name);
        }
        
        $errors = $this->get('validator')->validate($aire);
        
        if (count($errors) == 0) {    
            $this->getDoctrine()->getEntityManager()->persist($aire);
            $this->getDoctrine()->getEntityManager()->flush();
            $view->setData($aire)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new aire from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="texte", nullable=false, strict=true, description="texte du commentaire")
     * @RequestParam(name="aire_id", nullable=false, strict=true, description="aire id")
     */
    
    public function postCommentAction(ParamFetcher $paramFetcher)
    {
        $commentManager = $this->get('applisun_aire_jeux.comment_manager');
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $comment = $commentManager->createComment();
        $view = View::create(); 
        $aire = $aireManager->getAire($paramFetcher->get('aire_id'));
        $user = $this->getUser();
        $comment->setUser($user);
        if ($aire->hasUserAlreadyCommented($user))
        {
            $view->setData(UTF8_encode('Vous avez déjà commenté sur cette aire !'));
            $view->setStatusCode(400);
            return $view;
        }
                
        $comment->setTexte($paramFetcher->get('texte'));        
        $comment->setAire($aire);        
        $errors = $this->get('validator')->validate($comment);
        
        if (count($errors) == 0) {    
            $this->getDoctrine()->getEntityManager()->persist($comment);
            $this->getDoctrine()->getEntityManager()->flush();
            $view->setData($aire)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }
    
     /**
     * Return an user identified by username/email.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Return an user identified by username/email",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $slug username or email
     *
     * @return View
     */
    public function getUserAction($slug)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUserByUsernameOrEmail($slug);
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $view = View::create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }            
    
    /**
     * Create a Token from the submitted data.<br/>
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new token from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=true, description="username.")
     * @RequestParam(name="password", nullable=false, strict=true, description="password.")
     * @RequestParam(name="salt", nullable=false, strict=true, description="salt.")
     *
     * @return View
     */
    public function postTokenAction(ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($paramFetcher->get('username'));
        if (!$user instanceof User) {
            $view->setStatusCode(404)->setData("Data received succesfully but with errors.");
            return $view;
        }
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($paramFetcher->get('password'), $paramFetcher->get('salt'));
        
        if ($user->getPassword() != $password){
            $view->setStatusCode(404)->setData("Erreur d'identification");
            return $view;
        }
        
        $header = $this->generateToken($paramFetcher->get('username'), $password);
        //XWSSE instead of X-WSSE
        $data = array('digest' => $header['digest'], 'nonce' => $header['nonce'], 'created' => $header['created']);
        //$view->setHeader("Authorization", 'WSSE profile="UsernameToken"');
        //$view->setHeader("X-WSSE", $header['token']);
        //$serializer = $this->get('jms_serializer');
        //$data = json_encode($data);
        //return new Response($data, 200);
        $token = $header['digest'].'||'.$header['nonce'].'||'.$header['created'];
        $view->setStatusCode(200)->setData($token);
        return $view;
    }
            
    
    /**
     * Generate token for username given
     *
     * @param  string $username username
     * @param  string $password password with salt included
     * @return string
     */
    private function generateToken($username, $password)
    {
        $created = date('c');
        $nonce = substr(md5(uniqid('nonce_', true)), 0, 16);
        $nonceSixtyFour = base64_encode($nonce);
        $passwordDigest = base64_encode(sha1($nonce . $created . $password, true));
        $token = sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $username,
            $passwordDigest,
            $nonceSixtyFour,
            $created
        );
        return array('token' => $token, 'digest' => $passwordDigest, 'nonce' => $nonceSixtyFour, 'created' => $created);
    }
    
    /**
     * Get the validation errors
     *
     * @param ConstraintViolationList $errors Validator error list
     *
     * @return View
     */
    protected function getErrorsView(ConstraintViolationList $errors)
    {
        $msgs = array();
        $errorIterator = $errors->getIterator();
        foreach ($errorIterator as $validationError) {
            $msg = $validationError->getMessage();
            $params = $validationError->getMessageParameters();
            $msgs[$validationError->getPropertyPath()][] = $this->get('translator')->trans($msg, $params, 'validators');
        }
        $view = View::create($msgs);
        $view->setStatusCode(400);
        return $view;
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Post a new vote",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="score", nullable=false, strict=true, description="score")
     * @RequestParam(name="aire_id", nullable=false, strict=true, description="aire id")
     */
    
    public function postVoteAction(ParamFetcher $paramFetcher)
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');

        $aire = $aireManager->getAire($paramFetcher->get('aire_id'));
        if ($aire === null) {
            throw $this->createNotFoundException('Aire non trouvée');
        }
        $view = View::create();
        $vote = $aireManager->getNewVote($aire);
        $user = $this->getUser();
        if ($aire->hasUserAlreadyVoted($user))
        {
            $view->setData(UTF8_encode('Vous avez déjà voté sur cette aire !'));
            $view->setStatusCode(400);
            return $view;
        }
        
        $vote->setScore($paramFetcher->get('score'));
        $vote->setUser($user);
        
        $aireManager->saveVote($vote);
        $errors = $this->get('validator')->validate($vote);
        
        if (count($errors) == 0) {    
            
            $view->setData($vote)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of near a position",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="latitude", nullable=false, strict=true, description="latitude")
     * @QueryParam(name="longitude", nullable=false, strict=true, description="longitude")
     * @QueryParam(name="perimeter", nullable=false, strict=true, description="perimeter")
     */
    
    public function getNearAction(ParamFetcher $paramFetcher)
    {
        $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getNearAires($paramFetcher->get('latitude'), 
                                                                                                    $paramFetcher->get('longitude'),
                                                                                                     $paramFetcher->get('perimeter'));
                
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aires, "json");
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return Aire by id",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the aire is not found"
     *   }
     * )
     */
    
    public function getPlaygroundAction($id, $_format="json")
    {
        $aireManager = $this->get('applisun_aire_jeux.aire_manager');
        $aire = $aireManager->getAire($id);
        
        if (!$aire instanceof Aire) {
            throw $this->createNotFoundException('Aucune aire trouvée !');
        }
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aire, $_format);
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the overall Aire List by ville",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ville is not found"
     *   }
     * )
     */
    
    public function getCityAction($ville_id, $_format="json")
    {
        $aires = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Aire')->getAllAireByVille($ville_id);
        
        //$user = $this->getUser();
        //echo 'toto'; 
        //var_dump($user); exit;
        
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($aires, $_format);
        
        return new response($data, 200);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get list of near a position",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @QueryParam(name="latitude", nullable=false, strict=true, description="latitude")
     * @QueryParam(name="longitude", nullable=false, strict=true, description="longitude")
     */
    
    public function getCloseCityAction(ParamFetcher $paramFetcher)
    {
        $villes = $this->getDoctrine()->getRepository('ApplisunAireJeuxBundle:Ville')->getNearCity($paramFetcher->get('latitude'),$paramFetcher->get('longitude'));
                
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($villes, "json");
        
        return new response($data, 200);
    }
    
    
    
}

