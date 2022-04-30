<?php

namespace App\Service;

use App\Entity\Block;
use App\Repository\BlockRepository;
use DateTime;
use PhpParser\Node\Expr\Cast\Array_;

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

    public function clear(): void
    {
        //
    }
}