<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Etape;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RecetteFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create('fr_BE');

        $user = new User;
        $user->setUsername('demo');
        $user->setPassword($this->encoder->encodePassword($user, 'demo'));
        $user->setMail('demo@demo.com');
        $manager->persist($user);

    	for ($i = 0; $i < 20; $i++)
    	{
    		$recette = new Recette();
    		$recette
    			->setTitre($faker->words(3, true))
    			->setType($faker->numberBetween(0, count(Recette::RECETTE_TYPE) - 1))
    			->setNbrPers($faker->numberBetween(1, 8))
    			->setTempsPrepa(new \DateTime($faker->time($format = 'H:i', $max = 'now')))
                ->setTempsCuisson(new \DateTime($faker->time($format = 'H:i', $max = 'now')))
    			//->setCreatedAt($faker->dateTimeBetween('-100 days', '-1 days'))
                //->setUpdatedAt($faker->dateTimeBetween('-100 days', '-1 days'))
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setUser($user);
    		;
    		$manager->persist($recette);

            for ($j = 0; $j < 3; $j++)
            {
                $etape = new Etape();
                $etape
                    ->setContenu($faker->sentence())
                    ->setRecette($recette);
                ;
                $manager->persist($etape);
            }

            for ($k = 0; $k < 5; $k++)
            {
                $ingredient = new Ingredient();
                $ingredient
                    ->setContenu($faker->words(3, true))
                    ->setRecette($recette);
                ;
                $manager->persist($ingredient);
            }
    	}
        $manager->flush();
    }
}