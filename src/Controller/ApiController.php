<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\PartenairePasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Partenaire;
use App\Entity\Compte;
use App\Entity\Depot;
use App\Repository\UserRepository;


/**
 * @Route("/api", name="api")
 */

class ApiController extends AbstractController
{

    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    /**
     * @Route("/ajoutp",name="ajout",methods={"POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function ajoutp(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        $random = random_int(100000, 999999);
        if (isset($values->rs, $values->ninea)) {
            $us = new Partenaire();
            $us->setRs($values->rs);
            $us->setNinea($values->ninea);
            $us->setAdresse($values->adresse);
            $us->setStatus($values->status);

            $com = new Compte();
            $com->setIdpartenaire($us);
            $com->setNumbcompte($random);

            $ad = new User();
            $ad->setIdpartenaire($us);
            $ad->setIdcompte($com);
            $ad->setUsername($values->username);
            $ad->setRoles($us->getRoles());
            $ad->setPassword($passwordEncoder->encodePassword($ad, $values->password));
            $entityManager->persist($com);
            $entityManager->persist($ad);
            $entityManager->persist($us);
            $entityManager->flush();


            $data = [
                'statuss' => 201,
                'messages' => 'Le partenaire  a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'statusss' => 500,
            'messagess' => 'Erreur!!'
        ];
        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->username, $values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($user->getRoles());
            $part = $this->getDoctrine()->getRepository(Partenaire::class)->find($values->idpartenaire);
            $user->setIdpartenaire($part);
            $user->setNomcomplet($values->nomcomplet);
            $user->setMail($values->mail);
            $user->setTel($values->tel);
            $user->setAdresse($values->adresse);
            $user->setStatus($values->status);
            $rec = $this->getDoctrine()->getRepository(Compte::class)->find($values->idcompte);
            $user->setIdcompte($rec);
            $errors = $validator->validate($user);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'statu' => 201,
                'messag' => 'L\'utilisateur a été créé'
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'stat' => 500,
            'mess' => 'Erreur!!!'
        ];
        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/caissier", name="caissier" , methods={"POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function superadmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->username, $values->password)) {
            $use = new User();
            $use->setUsername($values->username);
            $use->setPassword($passwordEncoder->encodePassword($use, $values->password));
            $use->setRoles(['ROLE_CAISSIER']);
            $part = $this->getDoctrine()->getRepository(Partenaire::class)->find($values->idpartenaire);
            $use->setIdpartenaire($part);
            $use->setNomcomplet($values->nomcomplet);
            $use->setMail($values->mail);
            $use->setTel($values->tel);
            $use->setAdresse($values->adresse);
            $use->setStatus($values->status);
            $errors = $validator->validate($use);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500);
            }
            $entityManager->persist($use);
            $entityManager->flush();

            $data = [
                's' => 201,
                'm' => 'L\'utilisateur a été créé'
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'st' => 500,
            'me' => 'Erreur'
        ];
        return new JsonResponse($data, 500);
    }



    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }
    /** 
     * @Route("/depot" , name="depot", methods={"POST"})
     * @IsGranted("ROLE_CAISSIER")
     */
    public function depot(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if (isset($values->montant)) {
            $compt = new Depot();
            if ($values->montant >= 75000) {
                $compt->setMontant($values->montant);
                $compt->setDate(new \DateTime());
                $rec = $this->getDoctrine()->getRepository(Compte::class)->findOneBy(['numbcompte'=>$values->numbcompte]);
                $compt->setIdcompte($rec);
                $rec->setSolde($rec->getSolde() + $values->montant);
                $errors = $validator->validate($compt);
                if (count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500);
                }
            }
            $entityManager->persist($compt);
            $entityManager->flush();
            $data = [
                'stat' => 201,
                'sms' => 'depot reussie'
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'sta' => 500,
            'messg' => 'Erreur'
        ];
        return new JsonResponse($data, 500);
    }
    // update
    /**
     * @Route("/partenaire/{id}", name="update_phone", methods={"PUT"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function update(Request $request, SerializerInterface $serializer, Partenaire $partenaire, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $phoneUpdate = $entityManager->getRepository(Partenaire::class)->find($partenaire->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set' . $name;
                $phoneUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($phoneUpdate);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le partenaire a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/show/{id}", name="show_partenaire", methods={"GET"})
     */
    public function show(Partenaire $partenaire, PartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {
        $partenaire = $partenaireRepository->find($partenaire->getId());
        $data = $serializer->serialize($partenaire, 'json', [
            'groups' => ['show']
        ]);
        var_dump($data);
        return new Response($data, 200);
    }
    /** 
     * @Route("/bloquer" , name="bloquer", methods={"POST"})
     */
    public function bloquerdebloquer(Request $request, UserRepository $userRepo, EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $user = $userRepo->findOneByUsername($values->username);
        if ($user->getStatus() == "debloquer") {
            $user->SetStatus("bloquer");
            $user->SetRoles(["ROLE_USERLOCK"]);
            $entityManager->flush();
            $data = [
                'statu' => 200,
                'messag' => 'utilisateur bloquer'
            ];
            return new JsonResponse($data);
        } else {
            $user->SetStatus("debloquer");
            $user->SetRoles(["ROLE_USERUNLOCK"]);

            $entityManager->flush();
            $data = [
                'status' => 200,
                'message' => 'utilisateur debloquer'
            ];
            return new JsonResponse($data);
        }
    }
}
