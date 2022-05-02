<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class BlockService
{
    protected $repository;

    public function __construct(BlockRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(Array $data): Block
    {
        $block = new Block;
        $block->setBatch(new \DateTime);
        $block->setBlockHeight($data['block_height']);
        $block->setEntry($data['entry']);
        $block->setHash($data['hash']);
        $block->setNonce($data['nonce']);
        $block->setTries($data['tries']);

        $this->repository->add($block);

        return $block;
    }

    public function paginate(Int $page, $filter = '')
    {
        $query = $this->repository->createQueryBuilder('b', 'b.id')
            ->select('b')
            ->addSelect('b.batch', 'b.block_height', 'b.entry', 'b.nonce')
            ->orderBy('b.batch', 'ASC');
        $adapter = new QueryAdapter($query);

        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage(10);
        $paginator->setCurrentPage($page);

        return $paginator->getCurrentPageResults();
    }

    public function clear(): void
    {
        //
    }
}