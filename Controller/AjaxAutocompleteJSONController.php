<?php

namespace Shtumi\UsefulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Response;

class AjaxAutocompleteJSONController extends Controller
{

    public function getJSONAction()
    {

        $em = $this->get('doctrine')->getManager();
        $request = $this->getRequest();

        $entities = $this->get('service_container')->getParameter('shtumi.autocomplete_entities');

        $entity_alias = $request->get('entity_alias');
        $entity_inf = $entities[$entity_alias];

        if (false === $this->get('security.context')->isGranted( $entity_inf['role'] )) {
            throw new AccessDeniedException();
        }

        $letters = $request->get('letters');
        $maxRows = $request->get('maxRows');

        switch ($entity_inf['search']){
            case "begins_with":
                $like = $letters . '%';
            break;
            case "ends_with":
                $like = '%' . $letters;
            break;
            case "contains":
                $like = '%' . $letters . '%';
            break;
            default:
                throw new \Exception('Unexpected value of parameter "search"');
        }

	$property = $entity_inf['property'];

        if ($entity_inf['case_insensitive']) {
                $where_clause_lhs = 'WHERE LOWER(e.' . $property . ')';
                $where_clause_rhs = 'LIKE LOWER(:like)';
        } else {

                $where_clause_lhs = 'WHERE e.' . $property;
                $where_clause_rhs = 'LIKE :like';
        }



        $results = $em->createQuery(
            'SELECT e.' . $property . '
             FROM ' . $entity_inf['class'] . ' e ' .
             $where_clause_lhs . ' ' . $where_clause_rhs . ' ' .
            'ORDER BY e.' . $property)
            ->setParameter('like', $like )
            ->setMaxResults($maxRows)
            ->getScalarResult();

        $res = array();
        foreach ($results AS $r){
            $res[] = $r[$entity_inf['property']];
        }

        return new Response(json_encode($res));

    }
}
