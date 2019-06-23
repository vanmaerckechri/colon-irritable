<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Etape;
use App\Entity\Ingredient;
use App\Entity\Comment;
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
    private $users = array();
    private $recettes = array();

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create('fr_BE');

        for ($u = 0; $u < 3; $u++)
        {
            if ($u === 0)
            {
                $user = new User;
                $user->setUsername('demo' . $u)
                    ->setPassword($this->encoder->encodePassword($user, 'demo'))
                    ->setMail('demo@demo.com')
                ;
                array_push($this->users, $user);
                $manager->persist($user);
            }
            else
            {
                $user = new User;
                $user->setUsername('demo' . $u)
                    ->setPassword($this->encoder->encodePassword($user, 'demo'))
                    ->setMail($faker->email())
                ;
                array_push($this->users, $user);
                $manager->persist($user);
            }

        	for ($r = 0; $r < 5; $r++)
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
                array_push($this->recettes, $recette);
        		$manager->persist($recette);

                for ($e = 0; $e < 3; $e++)
                {
                    $etape = new Etape();
                    $etape
                        ->setContenu($faker->sentence())
                        ->setRecette($recette);
                    ;
                    $manager->persist($etape);
                }

                for ($i = 0; $i < 5; $i++)
                {
                    $ingredient = new Ingredient();
                    $ingredient
                        ->setContenu($faker->words(3, true))
                        ->setRecette($recette);
                    ;
                    $manager->persist($ingredient);
                }
        	}
        }

        foreach ($this->recettes as $recette)
        {
            foreach ($this->users as $user)
            {
                $comment = new Comment();
                $comment
                    ->setContenu($faker->sentence())
                    ->setScore($faker->numberBetween(1, 5))
                    ->setUser($user)
                    ->setRecette($recette)
                ;
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}