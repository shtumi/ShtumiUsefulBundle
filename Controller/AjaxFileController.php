<?php

namespace Shtumi\UsefulBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Response;

class AjaxFileController extends Controller
{

    public function uploadAction()
    {
        $request = $this->getRequest();

        $filesBag = $request->files->all();

        $files = array();
        $filesResult = array();
        //foreach ($filesBag as $form){
            foreach ($filesBag as $file){
                $files []= $file;
                $filesResult []=  array(
                    'path' => $file->getPathname(),
                    'url'  => 'ddd'
                );
            }
        //}

        $filesResult ['length'] = count($files);

        return new Response(json_encode(array(
            'result' => array(
                'files' => $filesResult
            )
        )));
    }
}
