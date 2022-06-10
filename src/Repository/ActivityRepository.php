<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\State;
use App\Repository\StateRepository;
use App\Form\Model\SearchActivityModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function add(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Activity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//    //     * @return Activity[] Returns an array of Activity objects
//    //     */
//    public function findByKeyWord($keyWord): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.name LIKE :keyWord')
//            ->setParameter('keyWord', '%'.$keyWord.'%')
//            ->orderBy('a.dateTimeBeginning', 'DESC')
////            ->setMaxResults(10) <= si je veux avoir un maximum de résultats
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
    //     * @return Activity[] Returns an array of Activity objects
    //     */
    public function findByFilters(SearchActivityModel $searchActivityModel, $currentParticipant, $stateRepository): array
    {
        $participantCampus = $searchActivityModel->getParticipantCampus();

        $nameKeyword = $searchActivityModel->getNameKeyword();

        $minDateTimeBeginning = $searchActivityModel->getMinDateTimeBeginning();
        $maxDateTimeBeginning = $searchActivityModel->getMaxDateTimeBeginning();

        $filterActiOrganized = $searchActivityModel->isFilterActiOrganized();

        $filterActiJoined = $searchActivityModel->isFilterActiJoined();

        $filterActiNotJoined = $searchActivityModel->isFilterActiNotJoined();

        $filterActiEnded = $searchActivityModel->isFilterActiEnded();

        // Realisation de la requete
        $queryBuilder = $this->createQueryBuilder('a');
        // Jointures
        $queryBuilder->join('a.state', 's');
        $queryBuilder->join('a.participants', 'p');
        $queryBuilder->join('a.organizer', 'o');
        $queryBuilder->join('a.place', 'pl');
        $queryBuilder->join('a.campus', 'c');
        $queryBuilder->addSelect('s', 'p', 'o', 'pl', 'c');
        //Chainage des conditions en fonction des filtres de recherche
        if ($participantCampus){
            $queryBuilder
                ->andWhere('a.campus = :campus')
                ->setParameter('campus', $participantCampus);
        }
        if ($nameKeyword){
            $queryBuilder
                ->andWhere('a.name LIKE :keyWord')
                ->setParameter('keyWord', '%'.$nameKeyword.'%');
        }
        if ($minDateTimeBeginning){
            $queryBuilder
                ->andWhere('a.dateTimeBeginning >= :minDateTimeBeginning')
                ->setParameter('minDateTimeBeginning', $minDateTimeBeginning);
        }
        if ($maxDateTimeBeginning){
            $queryBuilder
                ->andWhere(':maxDateTimeBeginning >= a.dateTimeBeginning')
                ->setParameter('maxDateTimeBeginning', $maxDateTimeBeginning);
        }

        if ($filterActiOrganized){
            $queryBuilder
                ->andWhere('a.organizer = :currentParticipant')
                ->setParameter('currentParticipant', $currentParticipant)
            ;
        }

        if ($filterActiJoined){
            $queryBuilder
                ->andWhere(':currentParticipant MEMBER OF a.participants')
                ->setParameter('currentParticipant', $currentParticipant)
            ;
        }
        if ($filterActiNotJoined){
            $queryBuilder
                ->andWhere(':currentParticipant NOT MEMBER OF a.participants')
                ->setParameter('currentParticipant', $currentParticipant)
            ;
        }

        if ($filterActiEnded){
            $state = $stateRepository->findOneBy(['wording' => 'Activity ended']);

            $queryBuilder
                ->andWhere('s = :state')
                ->setParameter('state', $state)

            ;
        }

        $created = $stateRepository->findOneBy(['wording' => 'Activity created']);
        $queryBuilder
            ->andWhere('(a.organizer = :user ) OR (a.organizer != :user AND a.state != :state)')
            ->setParameter('state', $created)
            ->setParameter('user', $currentParticipant);

        $queryBuilder->orderBy('a.dateTimeBeginning', 'DESC');

        //Récuperation des données en BDD suite à la requête
        $query = $queryBuilder->getQuery();

            return $query->getResult();

    }

    public function findActivitiesAndStates()
    {

        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder
            ->select('s', 'p', 'a')

            ->join('a.state', 's')
            ->join('a.participants', 'p');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Activity[] Returns an array of Activity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Activity
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
