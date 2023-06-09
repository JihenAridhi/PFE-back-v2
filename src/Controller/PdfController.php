<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\News;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Repository\NewsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class PdfController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private ArticleRepository $repoarticle;
    private EventRepository $repoevent;
    private NewsRepository $reponews;
    private ObjectManager $objectManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repoarticle = $this->managerRegistry->getRepository(Article::class);
        $this->repoevent = $this->managerRegistry->getRepository(Event::class);
        $this->reponews = $this->managerRegistry->getRepository(News::class);
        $this->objectManager = $this->managerRegistry->getManager();
    }
    /**
     * @Route("/generate-pdf", name="generate_pdf")
     */
    public function generatePdf(): Response
    {
        // Create a new PDF instance
        $pdf = new TCPDF();

        // Set PDF format and orientation
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Get the count of articles in a specific year
        $year = 2023; // Replace with the desired year
        $articleCount = $this->repoarticle->getArticleCountByYear($year);
        $eventCount = $this->repoevent->getEventCountByYear($year);
        $newsCount = $this->reponews->getNewsCountByYear($year);

        // Generate the HTML content
        $html = '<h1>Hello jiji </h1> ';
        $html .= '<p style="margin-top: 20px;">Number of articles in ' . $year .' : ' . $articleCount . '</p>';
        $html .= '<p style="margin-top: 60px;">Number of events in ' . $year .' : ' . $eventCount . '</p>';
        $html .= '<p style="margin-top: 100px;">Number of news in ' . $year .' : ' . $newsCount . '</p>';

        // Output the HTML as a PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output the PDF as a response
        return new Response($pdf->Output('reporting.pdf', 'I'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}