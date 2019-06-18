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

### Les Modifications en Cascades dans les Tables Relationnelle
`
// Dans l'entity =>
/**
 * @ORM\OneToMany(targetEntity="App\Entity\Etape", mappedBy="recette", orphanRemoval=true, cascade={"persist"})
 */
private nomdelentityaupluriel
`

### Installer et Configuer le Systeme d'Upload de Fichiers
`
// Installer le bundle.
composer require vich/uploader-bundle
// config/packages/vich_uploader.yaml
vich_uploader:
    db_driver: orm
    mappings:
      recette_image:
        uri_prefix: /images/recettes
        upload_destination: '%kernel.project_dir%/public/images/recettes'
        namer: Vich\UploaderBundle\Naming\UniqidNamer
// config/bundles.php
Vich\UploaderBundle\VichUploaderBundle::class => ['all' => true],
use Vich\Uploader\Doctrine\Mapping\Annotation as Vich;
// Dans le fichier de l'entity concernée
// dans l'annotation de la classe
/**
 * @Vich\Uploadable
 */
// ajouter les propriétés à la classe
/**
 * @ORM\Column(type="string", length=255)
 */
private $image;
/**
 * @var File|null
 * @Vich\UploadableField(mapping="recette_image", fileNameRecette="image")
 */
private $imageFile
/**
 *
 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
 */
public function setImageFile(?File $imageFile): void
{
    $this->imageFile = $imageFile;
    if (null !== $imageFile) {
        $this->updatedAt = new \DateTime();
    }
}
// dans le fichier template
{% if recette.image %}
	<img src="{{ vich_uploader_asset(recette, 'imageFile') }}" alt="photo du plat">
{% endif %}
`

### Les Thumbs Auto dans le Cache
`
// installer
composer require liip/imagine-bundle
// voir les fichiers services.yaml, ImageCacheSubscriber.php et liip_imagine.yaml dans config
`

### Divers Bundles
`
// Pour generer des valeurs dans les fixtures
fzaninotto/faker
`

### Filtres

`
// Saut de ligne.
{{ objet.colonne | nl2br }}
`

# Front

## Installer webpack
`composer require encore`
