<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\News;
use App\Entity\Partners;
use App\Entity\Person;
use App\Entity\Project;
use App\Repository\ArticleRepository;
use App\Repository\EventRepository;
use App\Repository\NewsRepository;
use App\Repository\PartnersRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class PdfController extends AbstractController
{
    private ManagerRegistry $managerRegistry;
    private ArticleRepository $repoarticle;
    private EventRepository $repoevent;
    private NewsRepository $reponews;
    private PartnersRepository $repopartners;
    private PersonRepository $repoperson;
    private ProjectRepository $repoproject;
    private ObjectManager $objectManager;



    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->repoarticle = $this->managerRegistry->getRepository(Article::class);
        $this->repoevent = $this->managerRegistry->getRepository(Event::class);
        $this->reponews = $this->managerRegistry->getRepository(News::class);
        $this->repopartners = $this->managerRegistry->getRepository(Partners::class);
        $this->repoperson = $this->managerRegistry->getRepository(Person::class);
        $this->repoproject = $this->managerRegistry->getRepository(Project::class);
        $this->objectManager = $this->managerRegistry->getManager();


    }

    /**
     * @Route("/generate-pdf", name="generate_pdf", methods={"POST"})
     */
    public function generatePdf(Request $request): Response
    {
        $year = $request->request->get('year');
        $checkboxes = json_decode($request->request->get('checkboxes'));

        // Access individual checkbox values
        $articlesChecked = in_array('Articles', $checkboxes);
        $eventsChecked = in_array('Events', $checkboxes);
        $projectsChecked = in_array('Projects', $checkboxes);
        $newsChecked = in_array('News', $checkboxes);
        $partnersChecked = in_array('Partners', $checkboxes);
        $membersChecked = in_array('Members', $checkboxes);

        // Create a new PDF instance
        $pdf = new TCPDF();

        // Set PDF format and orientation
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Generate the HTML content
        $html = '<h1 align="center">Reporting for SMARTLAB</h1>';
        $html .= '<p style="margin-top: 40px;" >This is a report to get some informations about smart laboratory for '. $year .'</p>';
        if($articlesChecked){
            $articles = $this->repoarticle->findAll();
            $articleCount = $this->repoarticle->getArticleCountByYear($year);
            $html .= '<div style="width: 70px; height: 50px; background-color: #ffffff; margin-top:40px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto;color: darkblue">Number of articles : ' . $articleCount . '</h3></div>';
            $html .= '<hr>';

            foreach ($articles as $article) {
                if ($article->getYear() == $year) {
                    $html .= '<hr>';
                    $html .= '<div >';
                    $html .= '<p><span style="color: dodgerblue;">Title</span> : '. $article->getTitle() . '<br><span style="color: dodgerblue;">Type</span> : '. $article->getType() .'<br><span style="color: dodgerblue;">Name of '. $article->getType() .' </span>: '. $article->getName() .'<br><span style="color: dodgerblue;">Description</span> : '. $article->getDescription() . '</p>';
                    $html .= '</div>';
                    $html .= '<hr>';
                }
            }
            $html .= '<hr>';

        }
        if($membersChecked){
            $personCount = $this->repoperson->getPersonCount();
            $html .= '<div style="width: 70px; height: 100px; background-color: #ffffff; margin-top:80px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto; color: darkblue">Number of members : ' . $personCount . '</h3></div>';
            $members = $this->repoperson->findAll();
            $html .='<table> <tr><th style="color: dodgerblue">First Name</th> <th style="color: dodgerblue">Last Name</th></tr>';
            foreach ($members as $member) {
                $html .= '<tr>';
                $html .= '<td> '. $member->getFirstName() . '</td><td> '. $member->getlastName() . '</td>';
                $html .= '<</tr>>';
            }


        }
        if($eventsChecked){
            $eventCount = $this->repoevent->getEventCountByYear($year);
            $html .= '<div style="width: 70px; height: 100px; background-color: #ffffff; margin-top:80px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto; color: darkblue">Number of events in ' . $year .' : ' . $eventCount . '</h3></div>';
        }
        if($projectsChecked){
            $projectCount = $this->repoproject->getProjectCountByYear($year);
            $html .= '<div style="width: 70px; height: 100px; background-color: #ffffff; margin-top:80px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto; color: darkblue">Number of events in ' . $year .' : ' . $projectCount . '</h3></div>';
        }
        if($newsChecked){
            $newsCount = $this->reponews->getNewsCountByYear($year);
            $html .= '<div style="width: 70px; height: 100px; background-color: #ffffff; margin-top:80px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto; color: darkblue">Number of news in ' . $year .' : ' . $newsCount . '</h3></div>';
        }
        if($partnersChecked){
            $partnersCount = $this->repopartners->getPartnersCount();
            $html .= '<div style="width: 70px; height: 100px; background-color: #ffffff; margin-top:80px; border-radius: 50px;"><h3 style="margin-top: 20px; width: 40px; height: auto; color: darkblue">Number of partners : ' . $partnersCount . '</h3></div>';
        }
        // Output the HTML as a PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output the PDF as a response
        $pdfContent = $pdf->Output('reporting.pdf', 'S');

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporting.pdf"',
        ]);

    }
}
