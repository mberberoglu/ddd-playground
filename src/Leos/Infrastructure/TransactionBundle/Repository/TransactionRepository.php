<?php

namespace Leos\Infrastructure\TransactionBundle\Repository;

use Leos\Domain\Transaction\Exception\TransactionNotFoundException;
use Leos\Domain\Transaction\Model\AbstractTransaction;
use Leos\Domain\Transaction\Repository\TransactionRepositoryInterface;

use Leos\Domain\Transaction\ValueObject\TransactionId;
use Leos\Infrastructure\Common\Doctrine\ORM\Repository\EntityRepository;

/**
 * Class TransactionRepository
 * 
 * @package Leos\Infrastructure\WalletBundle\Repository
 */
class TransactionRepository extends EntityRepository implements TransactionRepositoryInterface
{
    /**
     * @param TransactionId $transactionId
     *
     * @return AbstractTransaction
     *
     * @throws TransactionNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function get(TransactionId $transactionId): AbstractTransaction
    {
        $transaction = $this->createQueryBuilder('transaction')
            ->where('transaction.id = :id')
            ->setParameter('id', (string) $transactionId)
            ->getQuery()
            ->getSingleResult()
        ;

        if (!$transaction) {

            throw new TransactionNotFoundException();
        }

        return $transaction;
    }

    /**
     * @param AbstractTransaction $transaction
     */
    public function save(AbstractTransaction $transaction)
    {
        $this->_em->persist($transaction);
        $this->_em->flush();
    }
}
