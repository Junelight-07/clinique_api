<?php

        namespace App\Controller;

        use App\Entity\Consultation;
        use App\Repository\ConsultationRepository;
        use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
        use Symfony\Component\HttpFoundation\JsonResponse;
        use Symfony\Component\HttpFoundation\Request;
        use Symfony\Component\Routing\Annotation\Route;
        use Symfony\Component\Serializer\SerializerInterface;

        class ConsultationController extends AbstractController
        {
            private ConsultationRepository $consultationRepository;

            public function __construct(ConsultationRepository $consultationRepository)
            {
                $this->consultationRepository = $consultationRepository;
            }

            #[Route('/api/consultations/today', name: 'get_today_consultations', methods: ['GET'])]
            public function getTodayConsultations(Request $request, SerializerInterface $serializer): JsonResponse
            {
                $today = new \DateTime('today');
                $tomorrow = (new \DateTime('today'))->modify('+1 day');

                $consultations = $this->consultationRepository->createQueryBuilder('c')
                    ->select('c', 'a', 'ast', 'v') // Select joins to avoid N+1 queries
                    ->leftJoin('c.animal', 'a')
                    ->leftJoin('c.assistant', 'ast')
                    ->leftJoin('c.veterinaire', 'v')
                    ->andWhere('c.consultationDate >= :today')
                    ->andWhere('c.consultationDate < :tomorrow')
                    ->setParameter('today', $today)
                    ->setParameter('tomorrow', $tomorrow)
                    ->orderBy('c.consultationDate', 'ASC')
                    ->getQuery()
                    ->getResult();

                // Serialize the data manually with groups
                $json = $serializer->serialize($consultations, 'json', ['groups' => 'read']);

                return new JsonResponse($json, 200, [], true);
            }

            #[Route('/api/consultations/history', name: 'get_consultation_history', methods: ['GET'])]
            public function getConsultationHistory(Request $request, SerializerInterface $serializer): JsonResponse
            {
                // Check if user has appropriate role
//                $this->denyAccessUnlessGranted(['ROLE_VETERINARIAN', 'ROLE_ASSISTANT', 'ROLE_DIRECTOR']);

                $startDate = $request->query->get('startDate');
                $endDate = $request->query->get('endDate');

                if (!$startDate || !$endDate) {
                    return new JsonResponse(['error' => 'Start date and end date are required'], 400);
                }

                try {
                    $startDateTime = new \DateTime($startDate);
                    $endDateTime = new \DateTime($endDate);
                    // Add 1 day to end date to include the whole day
                    $endDateTime->modify('+1 day');
                } catch (\Exception $e) {
                    return new JsonResponse(['error' => 'Invalid date format. Use Y-m-d format.'], 400);
                }

                $consultations = $this->consultationRepository->createQueryBuilder('c')
                    ->select('c', 'a', 'ast', 'v')
                    ->leftJoin('c.animal', 'a')
                    ->leftJoin('c.assistant', 'ast')
                    ->leftJoin('c.veterinaire', 'v')
                    ->andWhere('c.consultationDate >= :startDate')
                    ->andWhere('c.consultationDate < :endDate')
                    ->setParameter('startDate', $startDateTime)
                    ->setParameter('endDate', $endDateTime)
                    ->orderBy('c.consultationDate', 'ASC')
                    ->getQuery()
                    ->getResult();

                // Serialize the data with groups
                $json = $serializer->serialize($consultations, 'json', ['groups' => 'read']);

                return new JsonResponse($json, 200, [], true);
            }
        }