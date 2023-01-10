<?php

namespace App\Test\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CardRepository $repository;
    private string $path = '/card/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Card::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Card index');

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
            'card[createdAt]' => 'Testing',
            'card[total]' => 'Testing',
            'card[valid]' => 'Testing',
            'card[user]' => 'Testing',
            'card[articles]' => 'Testing',
        ]);

        self::assertResponseRedirects('/card/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Card();
        $fixture->setCreatedAt('My Title');
        $fixture->setTotal('My Title');
        $fixture->setValid('My Title');
        $fixture->setUser('My Title');
        $fixture->setArticles('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Card');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Card();
        $fixture->setCreatedAt('My Title');
        $fixture->setTotal('My Title');
        $fixture->setValid('My Title');
        $fixture->setUser('My Title');
        $fixture->setArticles('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'card[createdAt]' => 'Something New',
            'card[total]' => 'Something New',
            'card[valid]' => 'Something New',
            'card[user]' => 'Something New',
            'card[articles]' => 'Something New',
        ]);

        self::assertResponseRedirects('/card/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getValid());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getArticles());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Card();
        $fixture->setCreatedAt('My Title');
        $fixture->setTotal('My Title');
        $fixture->setValid('My Title');
        $fixture->setUser('My Title');
        $fixture->setArticles('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/card/');
    }
}
