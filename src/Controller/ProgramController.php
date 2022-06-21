<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
    public function show(int $id, Program $program, SeasonRepository $seasonRepository): Response
    {
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
    #[Entity('program', options: ['id' => 'programId'])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response 
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table with this id.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season found in program\'s table with this id.'
            );
        }

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    #[Route('/program/{programId}/season/{seasonId}/episode/{episodeId}', name: 'program_episode_show')]
    #[Entity('program', options: ['id' => 'programId'])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    #[Entity('episode', options: ['id' => 'episodeId'])]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table with this id.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season found in program\'s table with this id.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode found in program\'s table with this id.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
