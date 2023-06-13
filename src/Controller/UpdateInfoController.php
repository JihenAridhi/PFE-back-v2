<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UpdateInfo
{

    /**
     * @Route("/api/footer", name="get_footer_data", methods={"GET"})
     */
    public function getFooterData()
    {
        $filePath ='C:\Users\roudeina\PhpstormProjects\PFE-back1\public\BrowseInfo\footer.json';
        $jsonData = file_get_contents($filePath);
        $footerData = json_decode($jsonData, true);

        return new JsonResponse($footerData);
    }

    /**
     * @Route("/api/update-footer", name="update_footer", methods={"POST"})
     */
    public function updateFooter(Request $request): Response
    {
        $payload = $request->getContent();
        var_dump($payload);
        $footerData = json_decode($payload, true);


        // Output the received data for debugging
        var_dump('hi');
        var_dump($footerData);
        var_dump('hello');

        // Update the footer.json file with the new data
        $filePath = 'C:\Users\roudeina\PhpstormProjects\PFE-back1\public\BrowseInfo\footer.json';
        $filesystem = new Filesystem();

        if ($filesystem->exists($filePath)) {

            $jsonData = json_encode($footerData, JSON_PRETTY_PRINT);
            $filesystem->dumpFile($filePath, $jsonData);

            return new JsonResponse('Footer data updated successfully', Response::HTTP_OK);
        } else {
            return new JsonResponse('Footer data file not found', Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Route("/api/president", name="get_president_data", methods={"GET"})
     */
    public function getPresidentData()
    {
        $filePath ='C:\Users\roudeina\PhpstormProjects\PFE-back1\public\BrowseInfo\president.json';
        $jsonData = file_get_contents($filePath);
        $presidentData = json_decode($jsonData, true);

        return new JsonResponse($presidentData);
    }

    /**
     * @Route("/api/update-president", name="update_president", methods={"POST"})
     */
    public function updatePresident(Request $request): Response
    {
        $payload = $request->getContent();
        var_dump($payload);
        $presidentData = json_decode($payload, true);


        // Output the received data for debugging
        var_dump('hi');
        var_dump($presidentData);
        var_dump('hello');

        // Update the footer.json file with the new data
        $filePath = 'C:\Users\roudeina\PhpstormProjects\PFE-back1\public\BrowseInfo\president.json';
        $filesystem = new Filesystem();

        if ($filesystem->exists($filePath)) {

            $jsonData = json_encode($presidentData, JSON_PRETTY_PRINT);
            $filesystem->dumpFile($filePath, $jsonData);

            return new JsonResponse('President data updated successfully', Response::HTTP_OK);
        } else {
            return new JsonResponse('President data file not found', Response::HTTP_NOT_FOUND);
        }
    }

}
