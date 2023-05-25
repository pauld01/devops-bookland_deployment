namespace App\Tests\Entity;

use App\Entity\Auteur;
use PHPUnit\Framework\TestCase;

class AuteurTest extends TestCase
{
    public function testNomPrenom()
    {
        $auteur = new Auteur();
        $nom_prenom = "nom_prenom_auteur";
        
        $auteur->setNomPrenom($nom_prenom);
        $this->assertEquals("nom_prenom_auteur", $auteur->getNomPrenom());
    }
}