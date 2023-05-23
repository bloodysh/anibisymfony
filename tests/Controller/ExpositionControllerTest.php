<?php

namespace App\Test\Controller;

use App\Entity\Exposition;
use App\Repository\ExpositionRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpositionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ExpositionRepository $repository;
    private string $path = '/exposition/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Exposition::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Exposition index');

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
            'exposition[nomExpo]' => 'Testing',
            'exposition[tarifAdulte]' => 'Testing',
            'exposition[tarifEnfant]' => 'Testing',
            'exposition[active]' => 'Testing',
        ]);

        self::assertResponseRedirects('/exposition/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Exposition();
        $fixture->setNomExpo('My Title');
        $fixture->setTarifAdulte('My Title');
        $fixture->setTarifEnfant('My Title');
        $fixture->setActive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Exposition');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Exposition();
        $fixture->setNomExpo('My Title');
        $fixture->setTarifAdulte('My Title');
        $fixture->setTarifEnfant('My Title');
        $fixture->setActive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'exposition[nomExpo]' => 'Something New',
            'exposition[tarifAdulte]' => 'Something New',
            'exposition[tarifEnfant]' => 'Something New',
            'exposition[active]' => 'Something New',
        ]);

        self::assertResponseRedirects('/exposition/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomExpo());
        self::assertSame('Something New', $fixture[0]->getTarifAdulte());
        self::assertSame('Something New', $fixture[0]->getTarifEnfant());
        self::assertSame('Something New', $fixture[0]->getActive());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Exposition();
        $fixture->setNomExpo('My Title');
        $fixture->setTarifAdulte('My Title');
        $fixture->setTarifEnfant('My Title');
        $fixture->setActive('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/exposition/');
    }
}
