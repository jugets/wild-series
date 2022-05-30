<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series', 'programs' => $programs
         ]);
    }

    #[Route('/new', name: 'new ')]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $programRepository->add($program, true);    
            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }
        $seasons = $seasonRepository->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', [
            'id' => $id,
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    #[Route('/{programId}/seasons/{seasonId}', name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response 
    {
        $program = $programRepository->findOneBy(['id' => $programId]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $programId . ' found in program\'s table.'
            );
        }

        $season = $seasonRepository->findOneBy(['program' => $program]);

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $seasonId . ' found in program\'s table.'
            );
        }

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'seasonId' => $seasonId,
            'programId' => $programId,
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }
}
