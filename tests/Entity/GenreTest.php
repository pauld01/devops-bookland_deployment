namespace App\Tests\Entity;

use App\Entity\Genre;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    public function testSetNom()
    {
        $genre = new Genre();
        $nom = "nom_genre";
        
        $genre->setNom($nom);
        $this->assertEquals("nom_genre", $auteur->getNom());
    }
}