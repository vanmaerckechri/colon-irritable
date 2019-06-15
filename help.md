# Back

## Les Bases de Données

### Creer une Base de Données
Configurer le fichier .env.
`php bin/console doctrine:database:create`

### Creer une Table et des Colonnes
`php bin/console make:entity`
`php bin/console make:migration`
`php bin/console doctrine:migrations:migrate`

### Ajouter des Colonnes à une Table
`php bin/console make:entity lenomdelatable`

### Ajouter des Données
`
// Directement dans la classe concernée =>
$lenomdelatable = new lenomdelatable();
$lenomdelatable->setLenomdelacolonne('mon premier titre')
	->setLenomdelautrecolonne('mon premier test2')
$em = $this->getDoctrine()->getManager();
$em->persist($lenomdelatable);
$em->flush();
// OU avec le constructeur du controlleur =>
use Doctrine\Common\Persistence\ObjectManager;
public function __construct(nomdelatableRepository $repository, ObjectManager $em)
{
	$this->repository = $repository;
	$this->em = $em;
}
public function index(TestRepository $repository) : Response
{
	$nomdelatable = new Nomdelatable();
	$nomdelatable->setNomdelacolonne('valeur')
		->setNomautrecolonne('valeur');
	$this->em->persist($nomdelatable);
	$this->em->flush();
}
`

### Accéder à une Table
`
// Directement dans la classe concernée =>
public function classeconcernée(): Response
{
	$repository = $this->getDoctrine()->getRepository(Test::class);
}
// OU via le constructeur du controlleur =>
use App\Repository\TestRepository;
public function __construct(lenomedelatableRepository $repository)
{
	$this->repository = $repository;
}
public function classeconcernée(): Response
{
	$this->repository;
}
// OU directement dans les attributs de la class concernée =>
use App\Repository\TestRepository;
public function classeconcernée(lenomdelatableRepository $repository): Response
{
	$repository;
}
`

### Accéder aux Rangées
`
$repository->find(numérodelid);
$repository->findAll();
$repository->findOneBy(['nomdelacolonne' => ayantpourvaleur]);
// Pour créer un custom find, jeter un coup d'oeil dans le repository concerné.
`

### Modifier des Données
`
use Doctrine\Common\Persistence\ObjectManager;
public function __construct(lenomedelatableRepository $repository, ObjectManager $em)
{
	$this->repository = $repository;
	$this->em = $em;
}
public function classeconcernée(): Response
{
	$nomdelatable = $this->repository->findOneBy(['nomdelacolone' => 'ayantpourvaleur']);
	$nomdelatable[0]->setNomColonne('new value');
	$this->em->flush();
}
// Pour d'autres manières de faire, regarder dans "Ajouter des Données".
`

### Filtres

`
// Saut de ligne.
{{ objet.colonne | nl2br }}
`

# Front

## Installer webpack
`composer require encore`
