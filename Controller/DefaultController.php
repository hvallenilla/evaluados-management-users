<?php

namespace Evaluados\UserBundle\Controller;

use Evaluados\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UsuariosBundle\Entity\Usuario;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_bundle_evaluados_users_list")
     * @Template("@EvaluadosUser/Default/index.html.twig")
     */
    public function indexAction()
    {
        # Fin Coordinators
        $users = $this->getByType(2);
        $columns = array('Nombre y Apellido', 'Email', 'Último ingreso');

        return [
            'columns' => $columns,
            'users' => $users
        ];
    }

    /**
     * @param Request $request
     * @Route("/new", name="app_bundle_evaluados_users_new")
     * @Template("@EvaluadosUser/Default/new.html.twig")
     * @return array
     */
    public function newAction(Request $request)
    {
        $user = new Usuario();
        $trans = $this->get('translator');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $extraData = $request->request->all();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword('12345678', $user->getSalt());
            $user->setPassword($password);

            #assign role (type user)
            $user->setRole(5);
            $user->setRoles('ROLES_TEACHER');
            $user->setStatusId(1);
            $type = $trans->trans('users.user.role.teacher');
            if ( $extraData['user_type'] === 'coord' ) {
                $type = $trans->trans('users.user.role.coordinator');
                $user->setRole(2);
                $user->setRoles('ROLES_COORDINATOR');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')
                ->getFlashBag()
                ->add('success', $trans->trans('users.new.flash.success', ['%user_type%' => $type]));

            return $this->redirectToRoute('app_bundle_evaluados_users_list');
        }

        return [
            'form' => $form->createView()
        ];
    }

    public function getByType($type)
    {
        $query = $this->getDoctrine()->getRepository('UsuariosBundle:Usuario')
            ->createQueryBuilder('u')
            ->where('u.type = :type')
            ->setParameter('type', $type);


        return $query->getQuery()->getArrayResult();
    }
}
