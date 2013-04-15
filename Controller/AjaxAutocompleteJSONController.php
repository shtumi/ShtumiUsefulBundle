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
        $em = $this->get('doctrine')->getEntityManager();
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

        $properties = $entity_inf['shown_properties'];
        $last_key = count($properties) - 1;
        $toSelect = null;

        foreach($properties as $key => $prop)
        {
            $toSelect .= 'e.'.$prop;
            if ($key != $last_key) $toSelect .= ', ';
        }

        $results = $em->createQuery(
            'SELECT '.$toSelect.'
             FROM ' . $entity_inf['class'] . ' e ' .
                $where_clause_lhs . ' ' . $where_clause_rhs . ' ' .
                'ORDER BY e.' . $property)
            ->setParameter('like', $like )
            ->setMaxResults($maxRows)
            ->getScalarResult();

        $final = array();

        foreach ($results as $key => $res)
        {
            $stringToPrint = null;
            $i = 0;
            $last_key = count($res) - 1;

            foreach($res as $r)
            {
                $stringToPrint .= $r;

                if ($i !== $last_key)
                {
                    $stringToPrint .= ' - ';
                }
                $i++;
            }

            $final[$key] = $stringToPrint;
        }

        return new Response(json_encode($final));
    }
}
