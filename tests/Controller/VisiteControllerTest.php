<?php

namespace App\Test\Controller;

use App\Entity\Visite;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisiteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private VisiteRepository $repository;
    private string $path = '/visite/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Visite::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Visite index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'visite[nbVisiteurAdulte]' => 'Testing',
            'visite[nbVisiteurEnfant]' => 'Testing',
            'visite[dateHeureArrivee]' => 'Testing',
            'visite[DateHeureDepart]' => 'Testing',
        ]);

        self::assertResponseRedirects('/visite/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Visite();
        $fixture->setNbVisiteurAdulte('My Title');
        $fixture->setNbVisiteurEnfant('My Title');
        $fixture->setDateHeureArrivee('My Title');
        $fixture->setDateHeureDepart('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Visite');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Visite();
        $fixture->setNbVisiteurAdulte('My Title');
        $fixture->setNbVisiteurEnfant('My Title');
        $fixture->setDateHeureArrivee('My Title');
        $fixture->setDateHeureDepart('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'visite[nbVisiteurAdulte]' => 'Something New',
            'visite[nbVisiteurEnfant]' => 'Something New',
            'visite[dateHeureArrivee]' => 'Something New',
            'visite[DateHeureDepart]' => 'Something New',
        ]);

        self::assertResponseRedirects('/visite/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNbVisiteurAdulte());
        self::assertSame('Something New', $fixture[0]->getNbVisiteurEnfant());
        self::assertSame('Something New', $fixture[0]->getDateHeureArrivee());
        self::assertSame('Something New', $fixture[0]->getDateHeureDepart());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Visite();
        $fixture->setNbVisiteurAdulte('My Title');
        $fixture->setNbVisiteurEnfant('My Title');
        $fixture->setDateHeureArrivee('My Title');
        $fixture->setDateHeureDepart('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/visite/');
    }
}
