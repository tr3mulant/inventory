<?php

namespace Stevebauman\Inventory\Tests\Transactions;

use Illuminate\Support\Facades\Lang;
use Stevebauman\Inventory\Models\InventoryTransaction;
use Stevebauman\Inventory\Tests\FunctionalTestCase;

/**
 * Inventory Transaction Back Order Test
 * 
 * @coversDefaultClass \InventoryTransaction
 */
class InventoryTransactionBackOrderTest extends FunctionalTestCase
{
    public function testInventoryTransactionBackOrder()
    {
        $transaction = $this->newTransaction();

        $transaction->backOrder(500);

        $this->assertEquals(500, $transaction->quantity);
        $this->assertEquals(InventoryTransaction::STATE_COMMERCE_BACK_ORDERED, $transaction->state);
    }

    public function testInventoryTransactionBackOrderFilled()
    {
        $transaction = $this->newTransaction();

        $transaction->backOrder(25);

        $stock = $transaction->getStockRecord();

        $stock->put(5);

        $transaction->fillBackOrder();

        $this->assertEquals(25, $transaction->quantity);
        $this->assertEquals(InventoryTransaction::STATE_COMMERCE_BACK_ORDER_FILLED, $transaction->state);
    }

    public function testInventoryTransactionBackOrderInvalidQuantityException()
    {
        $transaction = $this->newTransaction();

        $this->expectException('Stevebauman\Inventory\Exceptions\InvalidQuantityException');

        $transaction->backOrder('40s');
    }

    public function testInventoryTransactionBackOrderInvalidTransactionStateException()
    {
        $transaction = $this->newTransaction();

        $this->expectException('Stevebauman\Inventory\Exceptions\InvalidTransactionStateException');

        $transaction->checkout(5)->backOrder(3);
    }

    public function testInventoryTransactionBackOrderFilledDefaultReason()
    {
        $transaction = $this->newTransaction();

        $transaction->backOrder(5);

        $stock = $transaction->getStockRecord();

        Lang::shouldReceive('get')->once()->andReturn('test');

        $transaction->fillBackOrder();

        $this->assertEquals('test', $stock->reason);
    }
}
