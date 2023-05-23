namespace App\Tests\Entity;

use App\Entity\Livre;
use PHPUnit\Framework\TestCase;

class LivreTest extends TestCase
{
    public function testSetIsbn()
    {
        $livre = new Livre();
        $isbn = "978-2-7654-0912-0";
        
        $livre->setIsbn($isbn);
        $this->assertEquals("978-2-7654-0912-0", $livre->getIsbn());
    }

    public function testSetTitre()
    {
        $livre = new Livre();
        $titre = "un_monde";
        
        $livre->setTitre($titre);
        $this->assertEquals("un_monde", $livre->getTitre());
    }
}